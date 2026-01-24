<?php

namespace App\Observers;

use App\Models\StudentStoryTest;
use App\Models\UserTrackerStory;
use App\Services\AchievementLevelService;
use Illuminate\Support\Facades\DB;

class UserTrackerStoryObserver
{
    protected $achievementLevelService;

    public function __construct(AchievementLevelService $achievementLevelService)
    {
        $this->achievementLevelService = $achievementLevelService;
    }

    public function created(UserTrackerStory $userTrackerStory)
    {

        $tracker_count = UserTrackerStory::query()
            ->where("user_id", $userTrackerStory->user_id)
            ->where("story_id", $userTrackerStory->story_id)
            ->where("type", $userTrackerStory->type)
            ->where('points','!=',0)
            ->count();

        // Get points from service
        $points = $this->achievementLevelService->getPoints($userTrackerStory->type);


        // Add points to achievement system based on type
        DB::transaction(function () use ($userTrackerStory, $tracker_count, $points) {
            if ($tracker_count==0) {
                // Update the tracker record with points
                $userTrackerStory->update(['points' => $points]);

                // For non-test types, always add points
                if (in_array($userTrackerStory->type, ['watching', 'reading'])) {
                    $this->achievementLevelService->addPoints($userTrackerStory->type,$userTrackerStory->user_id);
                }
                // For test type, only add points if successful
                elseif ($userTrackerStory->type === 'test') {
                    $student_test = StudentStoryTest::where('story_id',$userTrackerStory->story_id)
                        ->where('user_id', $userTrackerStory->user_id)->first();

                    $mark = 25;

                    //if student pass test add points
                    if ($student_test && $student_test->total>=$mark) {
                        $this->achievementLevelService->addPoints($userTrackerStory->type,$userTrackerStory->user_id);
                    }

                }
            }
        });
    }


}
