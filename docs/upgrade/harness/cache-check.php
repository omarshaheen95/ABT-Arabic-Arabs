<?php
// Checks that values cached by one version are read back by the other. The keys
// hold the kinds of values nonarab caches: a Setting model and a Collection of
// them ('settings', see cached() and updateSettingsApi()), the settings array
// (SettingServiceProvider), and a student's dashboard data
// (UserDataCacheService: arrays holding models).
//   php cache-check.php <project_dir> write
//   php cache-check.php <project_dir> read [true|false]   overrides cache.serializable_classes
//   php cache-check.php <project_dir> clean
//   php cache-check.php <project_dir> permissions          reads the spatie/laravel-permission cache
$dir = $argv[1];
require $dir.'/vendor/autoload.php';
$app = require $dir.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
// UserDashboard::getData() creates the student's achievement level row when
// there is none: nothing this script does is kept in the database.
Illuminate\Support\Facades\DB::beginTransaction();
register_shutdown_function(function () {
    Illuminate\Support\Facades\DB::rollBack();
});

$student = function () {
    // the student of SmokeRoutesTest
    $teacher = Illuminate\Support\Facades\DB::table('teachers')
        ->join('teacher_students', 'teacher_students.teacher_id', '=', 'teachers.id')
        ->where('teachers.school_id', 71)->where('teachers.active', 1)->where('teachers.approved', 1)
        ->whereNull('teachers.deleted_at')->whereNull('teacher_students.deleted_at')
        ->groupBy('teachers.id')->orderByRaw('count(*) desc')->orderBy('teachers.id')->value('teachers.id');
    $id = Illuminate\Support\Facades\DB::table('users')->join('teacher_students', 'teacher_students.user_id', '=', 'users.id')
        ->where('teacher_students.teacher_id', $teacher)->whereNull('teacher_students.deleted_at')
        ->where('users.active', 1)->where('users.archived', 0)->whereNull('users.deleted_at')
        ->orderByRaw('(select count(*) from student_tests where student_tests.user_id = users.id) + (select count(*) from student_story_tests where student_story_tests.user_id = users.id) desc')
        ->orderBy('users.id')->value('users.id');

    return App\Models\User::query()->find($id);
};

$values = function () use ($student) {
    $user = $student();
    Illuminate\Support\Facades\Auth::guard('web')->setUser($user);
    app()->setLocale('en');

    return [
        'model' => App\Models\Setting::query()->first(),
        'collection' => App\Models\Setting::query()->get(),
        'array' => App\Models\Setting::query()->get()->pluck('value', 'key')->all(),
        'dashboard' => (new App\Services\UserDashboard())->getData(),
    ];
};

// json_encode of the value plus the class of every object inside it
$describe = function ($value) use (&$describe) {
    if (is_object($value)) {
        $out = get_class($value);
        if ($value instanceof Illuminate\Support\Collection) {
            $out .= '['.implode(',', $value->map(function ($v) use ($describe) { return $describe($v); })->all()).']';
        }
        return $out;
    }
    if (is_array($value)) {
        $parts = [];
        foreach ($value as $k => $v) {
            $parts[] = $k.':'.$describe($v);
        }
        return '{'.implode(',', $parts).'}';
    }
    return gettype($value);
};

if ($argv[2] === 'write') {
    // what the writer saw, as plain strings, for the reader to compare with
    $prints = [];
    foreach ($values() as $name => $value) {
        cache()->put('upgrade_check_'.$name, $value, 600);
        $prints[$name] = $describe($value).' '.json_encode($value);
    }
    cache()->put('upgrade_check_prints', $prints, 600);
    echo "written\n";
    exit;
}
if ($argv[2] === 'clean') {
    foreach (['model', 'collection', 'array', 'dashboard', 'prints'] as $name) {
        cache()->forget('upgrade_check_'.$name);
    }
    exit;
}
if ($argv[2] === 'permissions') {
    // what a manager's permission check sees with the cache as it is now
    try {
        $registrar = app(Spatie\Permission\PermissionRegistrar::class);
        $count = $registrar->getPermissions()->count();
        echo 'permissions loaded: '.$count."\n";
        $manager = App\Models\Manager::query()->orderBy('id')->first();
        echo 'manager 1 can "show activity logs": '.json_encode($manager->hasPermissionTo('show activity logs', 'manager'))."\n";
    } catch (Throwable $e) {
        echo get_class($e).': '.strtok($e->getMessage(), "\n")."\n";
    }
    exit;
}

if (isset($argv[3])) {
    config(['cache.serializable_classes' => $argv[3] === 'true']);
    // the store was already built while booting (SettingServiceProvider reads the cache)
    app('cache')->forgetDriver(config('cache.default'));
}
echo 'serializable_classes: '.json_encode(config('cache.serializable_classes'))."\n";
$prints = cache()->get('upgrade_check_prints', []);
foreach ($prints as $name => $expected) {
    try {
        $got = cache()->get('upgrade_check_'.$name);
        $same = $describe($got).' '.json_encode($got) === $expected;
        echo str_pad($name, 11).': '.(is_object($got) ? get_class($got) : gettype($got)).' '.($same ? 'same' : 'DIFFERENT '.substr($describe($got), 0, 200))."\n";
    } catch (Throwable $e) {
        echo str_pad($name, 11).': '.get_class($e).': '.strtok($e->getMessage(), "\n")."\n";
    }
}
