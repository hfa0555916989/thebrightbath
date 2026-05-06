<?php
$token = $_GET['token'] ?? '';
if ($token !== 'brightbath2026setup') { die('Unauthorized'); }

echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:13px;'>";
echo "=== THEBRIGHTBATH SETUP ===\n\n";

// Find Laravel root
$possibleRoots = [
    dirname(__DIR__),
    dirname(dirname(__DIR__)),
    '/home/u354011138/thebrightbath',
    '/home/u354011138/public_html/../',
];

$laravelRoot = null;
foreach ($possibleRoots as $path) {
    if (file_exists($path . '/artisan')) {
        $laravelRoot = realpath($path);
        break;
    }
}

echo "Laravel root: " . ($laravelRoot ?? 'NOT FOUND') . "\n";
echo "Script dir: " . __DIR__ . "\n";
echo "Parent dir: " . dirname(__DIR__) . "\n\n";

if (!$laravelRoot) {
    // Show what's in parent directories
    echo "Contents of " . dirname(__DIR__) . ":\n";
    $files = @scandir(dirname(__DIR__));
    if ($files) foreach ($files as $f) echo "  $f\n";
    die("\nERROR: Could not find Laravel root!");
}

$action = $_GET['action'] ?? 'status';
$php = PHP_BINARY;

echo "PHP: $php\n";
echo "Action: $action\n\n";

$artisan = $laravelRoot . '/artisan';

if ($action === 'pull') {
    echo "--- Git Pull ---\n";
    $out = shell_exec("cd $laravelRoot && git pull origin main 2>&1");
    echo $out . "\n";

} elseif ($action === 'migrate') {
    echo "--- Migrate ---\n";
    $out = shell_exec("cd $laravelRoot && $php artisan migrate --force 2>&1");
    echo $out . "\n";

} elseif ($action === 'seed') {
    echo "--- Seed SiteSettings ---\n";
    $out = shell_exec("cd $laravelRoot && $php artisan db:seed --class=SiteSettingsSeeder --force 2>&1");
    echo $out . "\n";
    echo "--- Seed ContentItems ---\n";
    $out = shell_exec("cd $laravelRoot && $php artisan db:seed --class=ContentItemsSeeder --force 2>&1");
    echo $out . "\n";

} elseif ($action === 'cache-clear') {
    echo "--- Clear Cache ---\n";
    $out = shell_exec("cd $laravelRoot && $php artisan config:clear && $php artisan cache:clear && $php artisan view:clear && $php artisan route:clear 2>&1");
    echo $out . "\n";

} elseif ($action === 'all') {
    echo "--- Running ALL steps ---\n\n";
    echo "1. Git Pull:\n";
    echo shell_exec("cd $laravelRoot && git pull origin main 2>&1") . "\n";
    echo "2. Migrate:\n";
    echo shell_exec("cd $laravelRoot && $php artisan migrate --force 2>&1") . "\n";
    echo "3. Seed:\n";
    echo shell_exec("cd $laravelRoot && $php artisan db:seed --class=SiteSettingsSeeder --force 2>&1") . "\n";
    echo shell_exec("cd $laravelRoot && $php artisan db:seed --class=ContentItemsSeeder --force 2>&1") . "\n";
    echo "4. Clear Cache:\n";
    echo shell_exec("cd $laravelRoot && $php artisan config:clear 2>&1") . "\n";
    echo shell_exec("cd $laravelRoot && $php artisan cache:clear 2>&1") . "\n";
    echo shell_exec("cd $laravelRoot && $php artisan view:clear 2>&1") . "\n";

} else {
    echo "Actions: pull | migrate | seed | cache-clear | all\n";
    echo "Status:\n";
    $out = shell_exec("cd $laravelRoot && $php artisan migrate:status 2>&1");
    echo $out . "\n";
}

echo "\n=== DONE ===\n</pre>";
