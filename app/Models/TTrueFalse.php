<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTrueFalse extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        't_question_id', 'result'
    ];
    protected static $recordEvents = ['updated'];
    public function t_question()
    {
        return $this->belongsTo(TQuestion::class);
    }
}
