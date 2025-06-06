<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class StoryUserRecord extends Model
{
    use SoftDeletes, Pathable,LogsActivityTrait;
    //status 'pending', 'corrected', 'returned'
    protected $fillable = [
        'user_id', 'story_id', 'record','feedback_audio_message','feedback_message', 'mark', 'approved', 'status'
    ];

    public $pathAttribute = [
        'record'
    ];

    public function getActionButtonsAttribute()
    {
        $actions = [
            ['key' => 'show', 'name' => t('Show'), 'route' => route(getGuard().'.stories_records.show', $this->id), 'permission' => 'show user records'],
            ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete user records'],
        ];

        return view('general.action_menu')->with('actions', $actions);

    }

    public function scopeFilter(Builder $query,$request=null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query
            ->when($value = $request->get('id', false), function (Builder $query) use ($value) {
                $query->where('id', $value);
            })->when($value = $request->get('user_id', false), function (Builder $query) use ($value) {
                $query->where('user_id', $value);
            })->when($value = $request->get('user_name', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%');
                });
            })->when($value = $request->get('user_email', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('email', $value);
                });
            })->when($value = $request->get('gender', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('gender', $value);
                });
            })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
                $query->whereRelation('user','school_id', $value);
            })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
                $query->whereRelation('user.teacherUser', 'teacher_id', $value);

            })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('section', $value);
                });
            })->when($value = $request->get('student_grade', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('grade_id', $value);
                });
            })->when($value = $request->get('user_status', false), function (Builder $query) use ($value) {
                if ($value == 'active') {
                    $query->whereHas('user', function (Builder $query) use ($value) {
                        $query->where('active_to', '>=', now());
                    });
                } elseif ($value == 'expire') {
                    $query->whereHas('user', function (Builder $query) use ($value) {
                        $query->where(function ($q) {
                            $q->where('active_to', '<', now())->orWhereNull('active_to');
                        });
                    });
                }
            })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '>=', $value);
            })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '<=', $value);
            })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
                $query->whereHas('story', function (Builder $query) use ($value) {
                    $query->where('grade', $value);
                });
            })->when($value = $request->get('story_id', false), function (Builder $query) use ($value) {
                $query->where('story_id', $value);
            })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
                $query->where('status', $value);
            })
            ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value){
                $query->whereRelation('user.teacher.supervisor_teachers','supervisor_id',$value);
            })
            ->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == 'pending')
        {
            return t(ucfirst('Waiting list'));
        }else if($this->status == 'corrected'){
            return t(ucfirst('Marking Completed'));
        }else{
            return t(ucfirst('Send back'));
        }
    }
    public function getStatusNameClassAttribute()
    {
        if ($this->status == 'pending') {
            return '<span class="badge badge-primary">' . t(ucfirst('Waiting list')). '</span>';
        } else if ($this->status == 'corrected') {
            return '<span class="badge badge-success">' . t(ucfirst('Marking Completed')). '</span>';
        } else {
            return '<span class="badge badge-warning">' . t(ucfirst('Send back')). '</span>';
        }
    }

// Filter by users
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
