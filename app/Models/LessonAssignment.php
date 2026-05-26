<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class LessonAssignment extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'teacher_id', 'year_id','grade_id','lessons_ids','sections','deadline',
        'exclude_students','test_assignment'
        ];

    protected $casts = [
        'sections'=>'json',
        'lessons_ids'=>'json'
    ];



    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function userAssignments()
    {
        return $this->hasMany(UserAssignment::class, 'lesson_assignment_id');
    }
    public function getActionButtonsAttribute()
    {
        $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route(getGuard().'.lesson_assignment.edit',$this->id), 'permission' => 'delete lesson assignments'],
                ['key' => 'blank', 'name' => t('Users Assignments'), 'route' => route(getGuard().'.user_lesson_assignment.index',['lesson_assignment_id'=>$this->id]), 'permission' => 'show user lesson assignments'],
                ['key' => 'event', 'name' => t('Delete'), 'route' => $this->id,'class'=>'text-danger delete_assignment', 'permission' => 'delete lesson assignments'],
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
        })->when($value = $request->get('lesson_id', false), function (Builder $query) use ($value) {
            $query->whereJsonContains('lessons_ids', $value);
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->whereRelation('teacher.school','id',$value);
        })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
            $query->whereRelation('teacher','id',$value);
        })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
            $query->whereJsonContains('sections', $value);
        })->when($value = $request->get('year_id', false), function (Builder $query) use ($value) {
            $query->where('year_id', $value);
        })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
            $query->where('grade_id', $value);
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            });
    }
}
