<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryMatchResult extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'story_question_id', 'story_match_id', 'story_result_id', 'student_story_test_id', 'match_answer_uid'
    ];
    protected static $recordEvents = ['updated'];
    public function student_story_test()
    {
        return $this->belongsTo(StudentStoryTest::class);
    }

    public function question()
    {
        return $this->belongsTo(StoryQuestion::class, 'story_question_id');
    }

    public function match()
    {
        return $this->belongsTo(StoryMatch::class, 'story_match_id');
    }

    public function result()
    {
        return $this->belongsTo(StoryMatch::class, 'story_result_id');
    }
    public function match_answer_uid(): BelongsTo
    {
        return $this->belongsTo(StoryMatch::class, 'match_answer_uid', 'uid');
    }
}
