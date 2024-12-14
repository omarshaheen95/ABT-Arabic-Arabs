<?php

namespace App\Models;

use App\Notifications\SchoolResetPassword;
use App\Traits\LogActivityTrait;
use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
<<<<<<< HEAD
=======
use Spatie\Permission\Traits\HasRoles;
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9


class School extends Authenticatable
{
<<<<<<< HEAD
    use Notifiable, SoftDeletes,LogsActivityTrait;
=======
    use Notifiable, SoftDeletes,LogsActivityTrait,HasRoles;
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9


    protected $fillable = [
        'name', 'email', 'password', 'website', 'mobile', 'logo', 'active','student_login','lang', 'last_login','last_login_info'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function scopeFilter(Builder $query, $request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id',false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('name',false), function (Builder $query) use ($value) {
            $query->where('name','like','%' . $value. '%');
        })->when($value = $request->get('email',false), function (Builder $query) use ($value) {
            $query->where('email','like','%' . $value. '%');
        })->when($value = $request->get('mobile',false), function (Builder $query) use ($value) {
            $query->where('mobile', $value);
        })->when($value = $request->get('active',false), function (Builder $query) use ($value) {
            $query->where('active', $value!=2);
        })->when($value = $request->get('row_id',[]), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);;
        });
    }

    public function getActionButtonsAttribute()
    {
<<<<<<< HEAD
        $actions=[];
        if (\request()->is('manager/*')){
            $actions =  [
                ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.school.edit', $this->id),'permission'=>'edit schools'],
                ['key'=>'login','name'=>t('Login'),'route'=>route('manager.school.login', $this->id),'permission'=>'school login'],
                ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete schools'],
            ];
        }
        elseif (\request()->is('supervisor/*')){
            $actions =  [];
        }
=======
        $actions =  [
            ['key'=>'edit','name'=>t('Edit'),'route'=>route(getGuard() . '.school.edit', $this->id),'permission'=>'edit schools'],
            ['key'=>'login','name'=>t('Login'),'route'=>route(getGuard() . '.school.login', $this->id),'permission'=>'school login'],
            ['key' => 'blank', 'name' => t('Edit Permissions'), 'route' => route(getGuard().'.user_role_and_permission.edit',['user_guard'=>'school','id'=>$this->id]),'permission'=>'edit schools permissions'],
            ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete schools'],
        ];
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
        return view('general.action_menu')->with('actions',$actions);

    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SchoolResetPassword($token));
    }

    public function getLogoAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/school.png'):asset($value);
    }
    public function login_sessions()
    {
        return $this->morphMany(LoginSession::class, 'model');
    }
    public function students()
    {
        return $this->hasMany(User::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', 1);
    }

}
