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
        'question_id', 'content', 'ordered', 'uid'
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        $this->belongsTo(Question::class);
    }
}
