<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class UserTracker extends Model
{
    use SoftDeletes;
    //Type 'learn', 'practise', 'test'
    protected $fillable = [
        'user_id', 'lesson_id', 'type', 'color', 'start_at', 'end_at'
    ];

    public function scopeFilter(Builder $query, $request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query
            ->when($value = $request->get('lesson_id', false), function (Builder $query) use ($value) {
                $query->where('lesson_id', $value);
            })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
                $query->whereHas('lesson', function (Builder $query) use ($value) {
                        $query->where('grade_id', $value);
                });
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            })->when($value = $request->get('type', false), function (Builder $query) use ($value) {
                $query->where('type', $value);
            })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '>=', $value);
            })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '<=', $value);
            });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getTotalTimeAttribute()
    {
        if (!is_null($this->start_at) && !is_null($this->end_at))
        {
            return Carbon::parse($this->start_at)->diffInMinutes($this->end_at);
        }else{
            return '';
        }
    }

    public function getTypeTextAttribute()
    {
        switch ($this->type)
        {
            case 'learn':
                $content = "بدا تعلم";
                return $content;
            case 'practise':
                $content = "بدا تدريب";
                return $content;
            case 'test':
                $content = "بدا اختبار";
                if (!is_null($this->start_at) && !is_null($this->end_at))
                {
                    $start = Carbon::parse($this->start_at);
                    $end = Carbon::parse($this->end_at);
                    $diff = $start->diffInMinutes($end, false);
                    $content .= ' <span class="badge badge-info">'.$diff.' د </span>';
                }
                return $content;
            default:
                return $this->type;
        }
    }


    // Filter by users (schools, grades, year, guard, and guard_user)
    public function scopeFilterByUsers(Builder $query, $schools, $grades, $year, $guard, $guard_user)
    {
        return $query->whereHas('user', function (Builder $query) use ($schools, $grades, $year, $guard, $guard_user) {
            $query->filterByGradeAndYear($grades, $year)
                ->filterBySchools($schools)
                ->filterByGuard($guard, $guard_user);
        });
    }

    // Filter by date range
    public function scopeFilterByDateRange(Builder $query, $start_date, $end_date)
    {
        return $query->when($start_date, function (Builder $query) use ($start_date) {
            $query->whereDate('created_at', '>=', $start_date);
        })->when($end_date, function (Builder $query) use ($end_date) {
            $query->whereDate('created_at', '<=', $end_date);
        });
    }
}
