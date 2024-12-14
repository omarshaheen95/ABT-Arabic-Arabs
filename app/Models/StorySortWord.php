<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorySortWord extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
<<<<<<< HEAD
        'story_question_id', 'content', 'ordered',
=======
        'story_question_id', 'content', 'ordered','uid'
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }
}
