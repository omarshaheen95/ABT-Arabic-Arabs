<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryAssignment extends Model
{
    use SoftDeletes,LogsActivityTrait;

    protected $fillable = ['teacher_id', 'year_id','students_grade','stories_ids','sections','deadline', 'exclude_students','story_grade'];

    protected $casts = [
        'sections'=>'json',
        'stories_ids'=>'json'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class);
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
        })->when($value = $request->get('year_id', false), function (Builder $query) use ($value) {
            $query->where('year_id', $value);
        })->when($value = $request->get('story_grade', false), function (Builder $query) use ($value) {
            $query->where('story_grade', $value);
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        });
    }
}
