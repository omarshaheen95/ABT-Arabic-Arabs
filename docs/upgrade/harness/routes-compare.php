<?php
// Usage: php routes-compare.php routes-before.json routes-after.json
// Compares two routes-dump.php outputs. Middleware that only changed class
// (the app's copies of framework middleware that were deleted, the renamed
// spatie namespace) are mapped to their new name first; everything else must
// match, in the same order. App\Http\Middleware\Authenticate is kept as it is
// in nonarab, so it is compared as it is.
[$self, $beforeFile, $afterFile] = $argv;
$renames = [
    'App\Http\Middleware\EncryptCookies' => 'Illuminate\Cookie\Middleware\EncryptCookies',
    'App\Http\Middleware\VerifyCsrfToken' => 'Illuminate\Foundation\Http\Middleware\PreventRequestForgery',
    'App\Http\Middleware\RedirectIfAuthenticated' => 'Illuminate\Auth\Middleware\RedirectIfAuthenticated',
    'Spatie\Permission\Middlewares\\' => 'Spatie\Permission\Middleware\\',
    // the subclass that keeps the Laravel 8 session value (app/Http/Middleware)
    'Illuminate\Session\Middleware\AuthenticateSession' => 'App\Http\Middleware\AuthenticateSession',
];
// package routes that were replaced on purpose
$ignore = '#^(_debugbar|log-viewer)(/|$)#';

$load = function ($file) use ($renames, $ignore) {
    $out = [];
    foreach (json_decode(file_get_contents($file), true) as $r) {
        if (preg_match($ignore, $r['uri'])) {
            continue;
        }
        $r['mw'] = array_map(function ($m) use ($renames) {
            foreach ($renames as $old => $new) {
                if (strpos($m, $old) === 0) {
                    return $new.substr($m, strlen($old));
                }
            }
            return $m;
        }, $r['mw']);
        $out[$r['methods'].' '.$r['uri']] = $r;
    }
    return $out;
};
$a = $load($beforeFile);
$b = $load($afterFile);
echo 'routes: '.count($a).' before, '.count($b)." after (without debugbar and log-viewer)\n";
foreach (array_diff_key($a, $b) as $k => $r) {
    echo "only before: $k {$r['action']}\n";
}
foreach (array_diff_key($b, $a) as $k => $r) {
    echo "only after: $k {$r['action']}\n";
}
$same = 0;
foreach (array_intersect_key($a, $b) as $k => $r) {
    $diff = [];
    // "error" (a controller that cannot be built) is not compared: Laravel 8
    // throws while gathering its middleware, Laravel 13 skips it.
    foreach (['name', 'action', 'mw'] as $f) {
        if (($r[$f] ?? null) !== ($b[$k][$f] ?? null)) {
            $diff[] = "$f: ".json_encode($r[$f] ?? null)."\n    => ".json_encode($b[$k][$f] ?? null);
        }
    }
    if ($diff) {
        echo "$k\n  ".implode("\n  ", $diff)."\n";
    } else {
        $same++;
    }
}
echo "identical: $same\n";
