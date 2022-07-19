<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchResult extends Model
{
    use SoftDeletes;
    protected $fillable = [
       'question_id', 'match_id', 'result_id', 'user_test_id'
    ];

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
}
