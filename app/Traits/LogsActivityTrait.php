<?php
namespace App\Traits;


use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityTrait
{
    use LogsActivity;

//    protected static $recordEvents = ['deleted'];

    /**
     * The options activitylog 3 read from static properties: every attribute
     * but the timestamps and the last login columns, only the changed ones,
     * and no row when nothing changed. $recordEvents stays a static property.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at','updated_at','last_login','last_login_info'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
