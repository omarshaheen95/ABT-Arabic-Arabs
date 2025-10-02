<?php namespace App\Services;

use App\Models\AchievementLevel;
use App\Models\UserAchievementLevel;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AchievementLevelService
{
    private $points_list;
    public function __construct()
    {
        //for story and lesson
        $this->points_list = [
            'learn'=>5,
            'practise'=>10,
            'test'=>20,
            'play'=>5,
            'watching'=>5,
            'reading'=>10
        ];
    }

    public function addPoints($user_id,$type): bool
    {
        $user_achievement_level = UserAchievementLevel::with('achievementLevel')
            ->where('user_id',$user_id)
            ->where('achieved',false)
            ->first();

        // If no active level found, create the first level
        if (!$user_achievement_level) {
            $first_level = AchievementLevel::first();
            if (!$first_level) {
                return false;
            }
            //create the first level for user
            UserAchievementLevel::create([
                'user_id' => $user_id,
                'achievement_level_id' => $first_level->id,
                'points' => $this->getPoints($type),
                'achieved' => false
            ]);

        }else{
            //if found level

            $achievement_level = $user_achievement_level->achievementLevel;

            $points = $user_achievement_level->points + $this->getPoints($type);

            //check points count
            if ($points >= $achievement_level->required_points){
                $remaining_points = $points - $achievement_level->required_points;

                //update current level and make it is achieved
                $user_achievement_level->update([
                    'points' => $achievement_level->required_points,
                    'achieved' => true,
                    'achieved_at' => Carbon::now(),
                ]);

                //move to next level and check if next level is found
                $next_level = AchievementLevel::find($achievement_level->id+1);
                if ($next_level){
                    UserAchievementLevel::create([
                        'user_id'=>$user_id,
                        'achievement_level_id'=>$next_level->id,
                        'points'=>$remaining_points,
                        'achieved'=>false
                    ]);
                }

            }else{
                //Update the specific user_active_level instance
                $user_achievement_level->update([
                    'points'=>$points,
                ]);
            }
        }

       return true;

    }

    public function getAchievementLevels($user_id): Collection
    {
        return UserAchievementLevel::with('achievementLevel')->where('user_id',$user_id)->get();
    }

    public function getPoints($key): int
    {
        return $this->points_list[$key]??0;
    }
}
