<?php
// Usage: php media-urls.php <project_dir> <out.txt>
// Prints getUrl() / getPath() (and the minImage conversion of lesson images)
// of every media row, and an md5 of the whole list, to diff two versions.
$dir = $argv[1];
require $dir.'/vendor/autoload.php';
$app = require $dir.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$class = class_exists('Spatie\\MediaLibrary\\MediaCollections\\Models\\Media')
    ? 'Spatie\\MediaLibrary\\MediaCollections\\Models\\Media'
    : 'Spatie\\MediaLibrary\\Models\\Media';
$rows = $class::query()->orderBy('id')->get();
$root = str_replace('\\', '/', realpath($dir));
$n = 0;
$all = '';
foreach ($rows as $m) {
    $line = $m->id.' '.$m->getUrl().' '.$m->getPath();
    if ($m->collection_name === 'imageLessons') {
        $line .= ' '.$m->getUrl('minImage').' '.$m->getPath('minImage').' '.json_encode($m->hasGeneratedConversion('minImage'));
    }
    $line = str_replace($root, 'DIR', str_replace('\\', '/', $line));
    $all .= $line."\n";
    if ($n++ < 2 || in_array($m->id, [134, 2212], true)) {
        echo $line, "\n";
    }
}
file_put_contents($argv[2], $all);
echo count($rows), ' media, md5 ', md5($all), "\n";
