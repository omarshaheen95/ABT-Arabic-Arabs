<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class QMatch extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia,LogsActivityTrait;

    protected $table = 'matches';

    protected $fillable = [
        'question_id', 'uid','content', 'result',
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
            ->acceptsFile(acceptsUploadTypes('image'))
            ->singleFile();
    }
}
