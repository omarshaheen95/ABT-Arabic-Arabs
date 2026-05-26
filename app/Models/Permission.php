<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Permission extends SpatiePermission
{
    public function getActionButtonsAttribute()
    {
            $actions =  [
                ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.permission.edit', $this->id),'permission'=>'edit permissions'],
                ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete permissions'],
            ];

        return view('general.action_menu')->with('actions',$actions);

    }

    public function scopeFilter(Builder $query,$request = null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id',false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('row_id',[]), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        })->when($value = $request->get('guard_name',false), function (Builder $query) use ($value) {
            $query->where('guard_name', $value);
        })->when($value = $request->get('group',false), function (Builder $query) use ($value) {
            $query->where('group', $value);
        })->when($value = $request->get('name',false), function (Builder $query) use ($value) {
            $query->where('name','LIKE', '%'.$value.'%');
        });
    }
}
