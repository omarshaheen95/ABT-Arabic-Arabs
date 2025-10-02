<?php

namespace App\Observers;

use App\Models\Lesson;
use App\Models\StudentTest;
use App\Models\UserTracker;
use App\Services\AchievementLevelService;

class UserTrackerObserver
{
    protected $achievementLevelService;

    public function __construct(AchievementLevelService $achievementLevelService)
    {
        $this->achievementLevelService = $achievementLevelService;
    }


    public function created(UserTracker $userTracker)
    {
        $tracker_count = UserTracker::query()
            ->where("user_id", $userTracker->user_id)
            ->where("lesson_id", $userTracker->lesson_id)
            ->where("type", $userTracker->type)
            ->where('points','!=',0)
            ->count();

        // Get points from service
        $points = $this->achievementLevelService->getPoints($userTracker->type);

        // Add points to achievement system based on type
        if ($tracker_count==0) {

            // Update the tracker record with points
            $userTracker->update(['points' => $points]);

            // For non-test types, always add points
            if (in_array($userTracker->type, ['learn', 'practise', 'play'])) {
                $this->achievementLevelService->addPoints($userTracker->user_id, $userTracker->type);
            }
            // For test type, only add points if successful
            elseif ($userTracker->type === 'test') {
                $student_test = StudentTest::with('lesson.level')
                ->where('lesson_id',$userTracker->lesson_id)
                ->where('user_id', $userTracker->user_id)->first();

                $lesson = $student_test->lesson;

                $mark = $lesson->level->level_mark;

                //if student pass test add points
                if ($student_test && $lesson && $student_test->total>=$mark) {
                    $this->achievementLevelService->addPoints($userTracker->user_id, $userTracker->type);
                }

            }
        }
    }


}
