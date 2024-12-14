<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class QMatch extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait,LogsActivityTrait;

    protected $table = 'matches';

    protected $fillable = [
<<<<<<< HEAD
        'question_id', 'content', 'result',
=======
        'question_id', 'uid','content', 'result',
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('match')
            ->singleFile();
    }
}
