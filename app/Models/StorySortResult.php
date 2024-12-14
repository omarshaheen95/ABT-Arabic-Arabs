<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorySortResult extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
<<<<<<< HEAD
        'story_question_id', 'story_sort_word_id', 'student_story_test_id'
=======
        'story_question_id', 'story_sort_word_id', 'student_story_test_id', 'story_sort_answer_uid'
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

    public function sort_word()
    {
        return $this->belongsTo(StorySortWord::class, 'story_sort_word_id');
    }
}
