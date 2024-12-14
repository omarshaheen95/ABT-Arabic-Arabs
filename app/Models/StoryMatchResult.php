<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryMatchResult extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
<<<<<<< HEAD
        'story_question_id', 'story_match_id', 'story_result_id', 'student_story_test_id'
=======
        'story_question_id', 'story_match_id', 'story_result_id', 'student_story_test_id', 'match_answer_uid'
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
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
<<<<<<< HEAD
=======
    public function match_answer_uid(): BelongsTo
    {
        return $this->belongsTo(StoryMatch::class, 'match_answer_uid', 'uid');
    }
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
}
