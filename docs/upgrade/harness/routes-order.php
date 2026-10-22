<?php
// Usage: php routes-order.php <project_dir> > out.txt
// Prints the routes in the order they were registered, which decides which
// one answers when two patterns match the same URL (routes-dump.php sorts
// them). Package routes (debugbar, log viewer) are left out.
$dir = $argv[1];
require $dir.'/vendor/autoload.php';
$app = require $dir.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach ($app['router']->getRoutes() as $r) {
    if (preg_match('#^(_debugbar|log-viewer)(/|$)#', $r->uri())) {
        continue;
    }
    echo implode('|', array_diff($r->methods(), ['HEAD'])), ' ', $r->uri(), "\n";
}
