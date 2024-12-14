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

class MatchResult extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
<<<<<<< HEAD
       'question_id', 'match_id', 'result_id', 'user_test_id'
=======
       'question_id', 'match_id', 'result_id', 'user_test_id', 'match_answer_uid'
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

    public function match()
    {
        return $this->belongsTo(QMatch::class, 'match_id');
    }

    public function result()
    {
        return $this->belongsTo(QMatch::class, 'result_id');
    }
<<<<<<< HEAD
=======
    public function match_answer_uid(): BelongsTo
    {
        return $this->belongsTo(QMatch::class, 'match_answer_uid', 'uid');
    }
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
}
