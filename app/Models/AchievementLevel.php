<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementLevel extends Model
{
    use SoftDeletes, LogsActivityTrait;

    protected $fillable = ['name', 'required_points', 'badge_icon', 'description'];


//    protected static function boot()
//    {
//        parent::boot();
//        static::addGlobalScope('order', function (Builder $builder) {
//            $builder->orderBy('required_points', 'asc');
//        });
//    }

    public function getActionButtonsAttribute()
    {
        $actions = [
            ['key' => 'edit', 'name' => t('Edit'), 'route' => route('manager.achievement_levels.edit', $this->id), 'permission' => 'edit achievement levels'],
            ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete achievement levels'],
        ];
        return view('general.action_menu')->with('actions', $actions);
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->when($value = $request->get('name', false), function (Builder $query) use ($value) {
            $query->where(function ($query) use ($value) {
                $query->where(DB::raw('LOWER(name->"$.ar")'), 'like', '%' . strtolower($value) . '%');
                $query->orWhere(DB::raw('LOWER(name->"$.en")'), 'like', '%' . strtolower($value) . '%');
            });
        })->when($value = $request->get('required_points'), function (Builder $query) use ($value) {
            $query->where('required_points', $value);
        })->when($value = $request->get('description'), function (Builder $query) use ($value) {
            $query->where('description', 'like', '%' . $value . '%');
        });
    }

    // Relationship with UserAchievementLevel
    public function userAchievements()
    {
        return $this->hasMany(UserAchievementLevel::class);
    }

    // Get users who have achieved this level
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievement_levels')
            ->withTimestamps()
            ->withPivot('achieved_at');
    }
}

