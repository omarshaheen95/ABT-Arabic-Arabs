<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Role extends SpatieRole
{

    public function getActionButtonsAttribute()
    {
            $actions =  [
                ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.role.edit', $this->id),'permission'=>'edit roles'],
                ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete roles'],
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
        })->when($value = $request->get('name',false), function (Builder $query) use ($value) {
            $query->where('name','LIKE', '%'.$value.'%');
        });
    }
}
