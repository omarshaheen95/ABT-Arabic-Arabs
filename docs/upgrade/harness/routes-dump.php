<?php
// Usage: php routes-dump.php <project_dir> > out.json
// Prints every route with its resolved middleware. A route whose controller
// cannot be built (nonarab has one pointing at a missing class) keeps only the
// middleware declared on the route, and is marked with "error".
$dir = $argv[1];
require $dir.'/vendor/autoload.php';
$app = require $dir.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$out = [];
foreach ($app['router']->getRoutes() as $r) {
    $error = null;
    try {
        $mw = $app['router']->gatherRouteMiddleware($r);
    } catch (Throwable $e) {
        $error = get_class($e).': '.strtok($e->getMessage(), "\n");
        // what gatherRouteMiddleware() does, without the controller's own middleware
        $mw = (function ($route) {
            return $this->sortMiddleware(collect($route->middleware())->map(function ($name) {
                return (array) Illuminate\Routing\MiddlewareNameResolver::resolve($name, $this->middleware, $this->middlewareGroups);
            })->flatten());
        })->call($app['router'], $r);
    }
    $mw = array_map(function ($m) { return is_string($m) ? $m : get_class($m); }, $mw);
    $row = [
        'methods' => implode('|', array_diff($r->methods(), ['HEAD'])),
        'uri' => $r->uri(),
        'name' => $r->getName(),
        'action' => ltrim($r->getActionName(), chr(92)),
        'mw' => array_values($mw),
    ];
    if ($error) {
        $row['error'] = $error;
    }
    $out[] = $row;
}
usort($out, function ($a, $b) { return strcmp($a['methods'].' '.$a['uri'], $b['methods'].' '.$b['uri']); });
echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
