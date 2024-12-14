<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortWord extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
<<<<<<< HEAD
        'question_id', 'content', 'ordered',
=======
        'question_id', 'content', 'ordered', 'uid'
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        $this->belongsTo(Question::class);
    }
}
