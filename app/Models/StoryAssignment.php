<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9

class StoryAssignment extends Model
{
    use SoftDeletes,LogsActivityTrait;
<<<<<<< HEAD
    protected $fillable = [
        'user_id', 'story_id', 'test_assignment', 'done_test_assignment', 'completed', 'deadline', 'completed_at', 'deadline', 'completed_at'
    ];

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id,'permission'=>'delete story assignments'],

            ];
        } elseif (\request()->is('school/*')) {
            $actions = [];
        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id],
            ];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [];
        }
        return view('general.action_menu')->with('actions', $actions);

    }
    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->when($value = $request->get('id',false), function (Builder $query) use ($value){
            $query->where('id', $value);
        })->when($value = $request->get('user_id',false), function (Builder $query) use ($value){
            $query->where('user_id', $value);
        })->when($value = $request->get('username',false), function (Builder $query) use ($value){
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
            $query->whereHas('story', function (Builder $query) use ($value) {
                $query->where('grade', $value);
            });
        })->when($value = $request->get('story_id', false), function (Builder $query) use ($value) {
            $query->where('story_id', $value);
        })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '>=', $value);
        })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '<=', $value);
        })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
            $query->where('completed', $value != 2);
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('school_id', $value);
            });
        })->when($value = $request->get('student_grade', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('grade_id', $value);
            });
        })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->whereHas('teacherUser', function (Builder $query) use ($value) {
                    $query->where('teacher_id', $value);
                });
            });
        })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('section', $value);
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
        })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
            $query->whereHas('story', function (Builder $query) use ($value) {
                $query->where('grade', $value);
            });
        })->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value){
                $query->whereRelation('user.teacher.supervisor_teachers','supervisor_id',$value);
            })
            ->when($value = $request->get('row_id', false), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            });
    }


    //boot with query where has story
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('story', function (Builder $builder) {
            $builder->has('story');
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

    public function getSubmitStatusAttribute()
    {
        $status = "";
        if (!is_null($this->deadline) && !is_null($this->completed_at))
        {
            if ($this->deadline < $this->completed_at)
            {
                $status = t('late');
            }else{
                $status = "-";
            }
        }else{
            $status = '-';
        }
        return $status;
    }


=======

    protected $fillable = ['teacher_id','students_grade','stories_ids','sections','deadline', 'exclude_students','story_grade'];

    protected $casts = [
        'sections'=>'json',
        'stories_ids'=>'json'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function userStoryAssignments()
    {
        return $this->hasMany(UserStoryAssignment::class, 'story_assignment_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class,'students_grade');
    }

    public function getActionButtonsAttribute()
    {
        $actions = [
            ['key' => 'edit', 'name' => t('Edit'), 'route' => route(getGuard().'.story_assignment.edit',$this->id), 'permission' => 'delete story assignments'],
            ['key' => 'blank', 'name' => t('Users Assignments'), 'route' => route(getGuard().'.user_story_assignment.index',['story_assignment_id'=>$this->id]), 'permission' => 'show user story assignments'],
            ['key' => 'event', 'name' => t('Delete'), 'route' => $this->id,'class'=>'text-danger delete_assignment', 'permission' => 'delete story assignments'],
        ];

        return view('general.action_menu')->with('actions', $actions);

    }

    public function scopeFilter(Builder $query,$request = null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id', false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('story_id', false), function (Builder $query) use ($value) {
            $query->whereJsonContains('stories_ids', $value);
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->whereRelation('teacher.school','id',$value);
        })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
            $query->whereRelation('teacher','id',$value);
        })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
            $query->whereJsonContains('sections', $value);
        })->when($value = $request->get('students_grade', false), function (Builder $query) use ($value) {
            $query->where('students_grade', $value);
        })->when($value = $request->get('story_grade', false), function (Builder $query) use ($value) {
            $query->where('story_grade', $value);
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        });
    }
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
}
