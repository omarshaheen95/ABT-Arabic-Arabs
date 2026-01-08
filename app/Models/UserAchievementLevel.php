<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAchievementLevel extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','achievement_level_id','points','achieved_at','achieved'];

    public function achievementLevel(): BelongsTo
    {
        return $this->belongsTo(AchievementLevel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
