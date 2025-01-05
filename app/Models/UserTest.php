<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class UserTest extends Model
{
    use SoftDeletes,CascadeSoftDeletes,LogsActivityTrait;

    protected $fillable = [
        'user_id', 'lesson_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status', 'feedback_message', 'feedback_record'
    ];
    protected $cascadeDeletes = ['matchResults', 'optionResults', 'sortResults', 'trueFalseResults','speakingResults'
     ,'writingResults'];

    //boot with query where has lesson
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('lesson', function (Builder $builder) {
            $builder->has('lesson');
        });
    }


    public function getActionButtonsAttribute()
    {
        $actions = [];

        $actions[] = ['key' => 'show', 'name' => t('Preview Answers'), 'route' => route(getGuard().'.lessons_tests.preview_answers', $this->id), 'permission' => 'show lesson tests'];
        if (in_array($this->lesson->lesson_type, ['writing', 'speaking'])) {
            $actions [] = ['key' => 'show', 'name' => t('Correcting & Feedback'), 'route' => route(getGuard().'.lessons_tests.correcting_feedback_view', $this->id), 'permission' => 'show lesson tests'];
        }else{
            $actions [] = ['key' => 'show', 'name' => t('Correcting & Preview '), 'route' => route(getGuard().'.lessons_tests.correcting_view', $this->id), 'permission' => 'show lesson tests'];
        }
        if ($this->status == 'Pass') {
            $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route(getGuard().'.lessons_tests.certificate', $this->id), 'permission' => 'lesson tests certificate'];
        }
        $actions[] = ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete lesson tests'];


        return view('general.action_menu')->with('actions', $actions);

    }


    public function scopeFilter(Builder $query,$request = null): Builder
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
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('school_id', $value);
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
            })->when($value = $request->get('lesson_type', false), function (Builder $query) use ($value) {
                $query->whereHas('lesson', function (Builder $query) use ($value) {
                    $query->where('lesson_type', $value);
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
            })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
                $query->whereHas('lesson', function (Builder $query) use ($value) {
                    $query->where('grade_id', $value);

                });
            })->when($value = $request->get('lesson_id', false), function (Builder $query) use ($value) {
                $query->where('lesson_id', $value);
            })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
                $query->where('status', $value);
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            }) ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value){
                $query->whereRelation('user.teacher.supervisor_teachers','supervisor_id',$value);
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
    public function matchResults(): HasMany
    {
        return $this->hasMany(MatchResult::class, 'user_test_id');
    }

    public function optionResults(): HasMany
    {
        return $this->hasMany(OptionResult::class, 'user_test_id');
    }

    public function sortResults(): HasMany
    {
        return $this->hasMany(SortResult::class, 'user_test_id');
    }

    public function trueFalseResults(): HasMany
    {
        return $this->hasMany(TrueFalseResult::class, 'user_test_id');
    }
    public function speakingResults()
    {
        return $this->hasMany(SpeakingResult::class, 'user_test_id');
    }

    public function writingResults()
    {
        return $this->hasMany(WritingResult::class, 'user_test_id');
    }



    public function getReadingBenchmarkAttribute()
    {
        if ($this->total >= 61) {
            return 'Above the expectations';
        } elseif ($this->total >= 41 && $this->total <= 68) {
            return 'In line with the expectations';
        } else {
            return 'Below the expectations';
        }
    }

    public function getExpectationsAttribute()
    {
        if ($this->total >= 61) {
            return 'Above';
        } elseif ($this->total >= 41 && $this->total <= 68) {
            return 'In line';
        } else {
            return 'Below';
        }
    }
    public function getTotalPerAttribute()
    {
        return $this->total > 0 ? (($this->total) / 100 * 100) . '%' : '0%';
    }

    public function getStatusNameAttribute()
    {
        return t($this->status);
    }

//    public function getActionButtonsAttribute()
//    {
//        $button = '<a target="_blank" href="' . route('manager.term.student_test', $this->id) . '" class="btn btn-success">تصحيح </a>';
//        $button .= ' <button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
//        return $button;
//    }
//
//    public function getTeacherActionButtonsAttribute()
//    {
//        $button = "";
//        if (in_array($this->lesson->lesson_type, ['writing', 'speaking'])) {
//            if ($this->corrected) {
//                $button .= ' <a target="_blank" href="' . route('teacher.students_tests.show', $this->id) . '" class="btn btn-info">تم التصحيح </a>';
//            } else {
//                $button .= ' <a target="_blank" href="' . route('teacher.students_tests.show', $this->id) . '" class="btn btn-success">تصحيح </a>';
//            }
//        }
//        $button .= ' <a target="_blank" href="' . route('teacher.students_tests.preview', $this->id) . '" class="btn btn-success">معاينة </a>';
//
//        return $button;
//    }
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
