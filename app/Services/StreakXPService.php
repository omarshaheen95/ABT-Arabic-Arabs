<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserStreakReward;
use App\Models\UserAchievementLevel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * StreakXPService - Manages user login streak rewards
 *
 * HOW THE STREAK SYSTEM WORKS:
 * ============================
 *
 * 1. STREAK DEFINITION:
 *    - A streak is a count of consecutive days a user has logged in
 *    - Streak starts at 1 on first login
 *    - Increments by 1 each day the user logs in
 *    - Example: Login on Day 1, Day 2, Day 3 = 3-day streak
 *
 * 2. STREAK CALCULATION (UserDashboard::calculateCurrentStreak):
 *    - Checks if user logged in TODAY or YESTERDAY
 *    - If NO login today/yesterday → Streak = 0 (broken)
 *    - If YES → Count consecutive days backwards from today
 *    - Uses LoginSession table to check login dates
 *
 * 3. XP REWARDS:
 *    - Weekly Reward: Given ONCE at exactly 7 consecutive days
 *    - Monthly Reward: Given ONCE at exactly 30 consecutive days
 *    - Default Points: Weekly = 50 XP, Monthly = 200 XP
 *    - After earning rewards, no more XP is given even if streak continues
 *
 * 4. STREAK BREAK & RESET:
 *    - If user doesn't login for more than 1 day → Streak breaks → Resets to 0
 *    - When streak resets, all reward records are DELETED
 *    - User can earn the same rewards again when they rebuild their streak
 *
 * 5. REWARD TRACKING:
 *    - Each reward is stored in user_streak_rewards table
 *    - Records: user_id, type (weekly/monthly), streak_days, points_awarded, awarded_at
 *    - Prevents duplicate rewards for the same streak cycle
 *
 * 6. COMPLETE FLOW EXAMPLE:
 *    Cycle 1:
 *    - Days 1-6: Build streak (no rewards)
 *    - Day 7: Login → Streak = 7 → Award 50 XP (weekly) ✓
 *    - Days 8-29: Continue streak (no rewards)
 *    - Day 30: Login → Streak = 30 → Award 200 XP (monthly) ✓
 *    - Days 31+: Continue streak (no more rewards)
 *
 *    Break:
 *    - Day 40: User doesn't login → Streak = 0 → Delete all reward records
 *
 *    Cycle 2:
 *    - Day 41: Login again → Streak = 1
 *    - Days 42-46: Build streak
 *    - Day 47: Login → Streak = 7 → Award 50 XP again (weekly) ✓
 *    - Days 48-69: Continue streak
 *    - Day 70: Login → Streak = 30 → Award 200 XP again (monthly) ✓
 *
 * 7. SETTINGS (Configurable in database):
 *    - enable_weekly_streak_xp: Enable/disable weekly rewards
 *    - weekly_streak_xp_points: Points for weekly streak (default: 50)
 *    - weekly_streak_days_required: Days required for weekly (default: 7)
 *    - enable_monthly_streak_xp: Enable/disable monthly rewards
 *    - monthly_streak_xp_points: Points for monthly streak (default: 200)
 *    - monthly_streak_days_required: Days required for monthly (default: 30)
 *
 * 8. TRIGGERED ON:
 *    - Every user login (via UserEventSubscriber::handleUserLogin)
 *    - Runs after LoginSession is created
 */
class StreakXPService
{
    protected $userDashboard;

    public function __construct()
    {
        $this->userDashboard = new UserDashboard();
    }

    /**
     * Main entry point: Check and award streak XP to user
     *
     * This method is called every time a user logs in (via UserEventSubscriber)
     *
     * PROCESS:
     * 1. Check if weekly/monthly streaks are enabled in settings
     * 2. Calculate user's current consecutive login streak
     * 3. Reset reward records if streak just started (day 0 or 1)
     * 4. Check if user qualifies for weekly reward (exactly 7 days)
     * 5. Check if user qualifies for monthly reward (exactly 30 days)
     *
     * @param User $user The user who just logged in
     * @return void
     */
    public function checkAndAwardStreakXP(User $user)
    {
        // STEP 1: Get settings from database (cached)
        // These settings control whether streak rewards are active
        $weeklyEnabled = settingCache('enable_weekly_streak_xp') == '1';
        $monthlyEnabled = settingCache('enable_monthly_streak_xp') == '1';

        // If both are disabled, don't process anything
        if (!$weeklyEnabled && !$monthlyEnabled) {
            return;
        }

        // STEP 2: Calculate current streak
        // This counts consecutive days user has logged in
        // Returns 0 if streak is broken (no login yesterday/today)
        $currentStreak = $this->userDashboard->calculateCurrentStreak($user->id);

        // STEP 3: Reset rewards when streak is new (0 or 1 day)
        // WHY? When user breaks streak and starts over, they should be able
        // to earn the weekly and monthly rewards again
        // We delete old reward records so the system treats this as a fresh streak
        if ($currentStreak <= 1) {
            UserStreakReward::where('user_id', $user->id)->delete();
        }

        // STEP 4: Check for weekly streak reward (if enabled)
        // Only awards at exactly 7 days (not 14, 21, etc.)
        if ($weeklyEnabled) {
            $this->checkWeeklyStreak($user, $currentStreak);
        }

        // STEP 5: Check for monthly streak reward (if enabled)
        // Only awards at exactly 30 days (not 60, 90, etc.)
        if ($monthlyEnabled) {
            $this->checkMonthlyStreak($user, $currentStreak);
        }
    }

    /**
     * Check and award weekly streak XP
     *
     * RULE: Only awarded ONCE when user reaches exactly 7 consecutive days
     * NOT awarded at 14, 21, 28 days (only at 7)
     *
     * LOGIC:
     * 1. Get required days from settings (default: 7)
     * 2. Get reward points from settings (default: 50)
     * 3. Check if current streak equals exactly the required days
     * 4. Check if reward was already given for this streak cycle
     * 5. If not given yet, award the XP and record it
     *
     * EXAMPLE:
     * - Day 7: currentStreak = 7 → Award 50 XP ✓
     * - Day 8: currentStreak = 8 → Skip (not exactly 7)
     * - Day 14: currentStreak = 14 → Skip (not exactly 7)
     * - Streak breaks, user rebuilds
     * - Day 7 again: currentStreak = 7 → Award 50 XP again ✓
     *
     * @param User $user The user to check
     * @param int $currentStreak User's current consecutive login days
     * @return void
     */
    protected function checkWeeklyStreak(User $user, int $currentStreak)
    {
        // Get settings from cache/database
        $requiredDays = (int) (settingCache('weekly_streak_days_required') ?? 7);
        $points = (int) (settingCache('weekly_streak_xp_points') ?? 50);

        // CRITICAL: Only award at exactly 7 days, not multiples (14, 21, etc.)
        // This ensures reward is given ONCE per streak cycle
        if ($currentStreak !== $requiredDays) {
            return; // Not at the exact milestone, skip
        }

        // Check database to see if we already gave this reward
        // We check for: same user + type 'weekly' + exact streak_days (7)
        $lastAward = UserStreakReward::where('user_id', $user->id)
            ->where('type', 'weekly')
            ->where('streak_days', $currentStreak)
            ->first();

        if ($lastAward) {
            return; // Already awarded for this streak period, prevent duplicate
        }

        // Award the XP and record it in database
        $this->awardXP($user, 'weekly', $currentStreak, $points);
    }

    /**
     * Check and award monthly streak XP
     *
     * RULE: Only awarded ONCE when user reaches exactly 30 consecutive days
     * NOT awarded at 60, 90, 120 days (only at 30)
     *
     * LOGIC:
     * 1. Get required days from settings (default: 30)
     * 2. Get reward points from settings (default: 200)
     * 3. Check if current streak equals exactly the required days
     * 4. Check if reward was already given for this streak cycle
     * 5. If not given yet, award the XP and record it
     *
     * EXAMPLE:
     * - Day 30: currentStreak = 30 → Award 200 XP ✓
     * - Day 31: currentStreak = 31 → Skip (not exactly 30)
     * - Day 60: currentStreak = 60 → Skip (not exactly 30)
     * - Streak breaks, user rebuilds
     * - Day 30 again: currentStreak = 30 → Award 200 XP again ✓
     *
     * @param User $user The user to check
     * @param int $currentStreak User's current consecutive login days
     * @return void
     */
    protected function checkMonthlyStreak(User $user, int $currentStreak)
    {
        // Get settings from cache/database
        $requiredDays = (int) (settingCache('monthly_streak_days_required') ?? 30);
        $points = (int) (settingCache('monthly_streak_xp_points') ?? 200);

        // CRITICAL: Only award at exactly 30 days, not multiples (60, 90, etc.)
        // This ensures reward is given ONCE per streak cycle
        if ($currentStreak !== $requiredDays) {
            return; // Not at the exact milestone, skip
        }

        // Check database to see if we already gave this reward
        // We check for: same user + type 'monthly' + exact streak_days (30)
        $lastAward = UserStreakReward::where('user_id', $user->id)
            ->where('type', 'monthly')
            ->where('streak_days', $currentStreak)
            ->first();

        if ($lastAward) {
            return; // Already awarded for this streak period, prevent duplicate
        }

        // Award the XP and record it in database
        $this->awardXP($user, 'monthly', $currentStreak, $points);
    }

    /**
     * Award XP points to user and update their achievement level
     *
     * This method handles the actual XP award process:
     * 1. Records the reward in user_streak_rewards table
     * 2. Adds points to user's current achievement level
     * 3. Checks if user leveled up
     * 4. Creates next level if current level is completed
     *
     * All operations are wrapped in a database transaction to ensure
     * data consistency (either all succeed or all fail)
     *
     * TABLES AFFECTED:
     * - user_streak_rewards: Records when/how much XP was awarded
     * - user_achievement_levels: Updates user's current XP and level status
     *
     * EXAMPLE:
     * User has 80 XP, needs 100 XP for level up
     * - Award 50 XP → Now has 130 XP
     * - Current level marked as achieved ✓
     * - Next level automatically created
     * - User starts at 0 XP on new level
     *
     * @param User $user The user receiving the XP
     * @param string $type Reward type: 'weekly' or 'monthly'
     * @param int $streakDays The streak count when reward was given (7 or 30)
     * @param int $points Amount of XP to award (50 for weekly, 200 for monthly)
     * @return void
     */
    protected function awardXP(User $user, string $type, int $streakDays, int $points)
    {
        // Wrap everything in a transaction for data integrity
        // If any step fails, all changes are rolled back
        DB::transaction(function () use ($user, $type, $streakDays, $points) {

            // STEP 1: Record the reward in database
            // This prevents duplicate awards and provides audit trail
            UserStreakReward::create([
                'user_id' => $user->id,
                'type' => $type, // 'weekly' or 'monthly'
                'streak_days' => $streakDays, // 7 or 30
                'points_awarded' => $points, // 50 or 200
                'awarded_at' => Carbon::now(),
            ]);

            // STEP 2: Add Points
            $achievement_level_service = new AchievementLevelService();
            $achievement_level_service->addCustomPoints($points);

        });
    }
}
