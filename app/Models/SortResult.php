<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortResult extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
<<<<<<< HEAD
        'question_id', 'sort_word_id', 'user_test_id'
=======
        'question_id', 'sort_word_id', 'user_test_id' ,'sort_answer_uid',
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
    ];
    protected static $recordEvents = ['updated'];
    public function user_test()
    {
        return $this->belongsTo(UserTest::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function sort_word()
    {
        return $this->belongsTo(SortWord::class);
    }
}
