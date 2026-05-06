<?php
// Security token - delete this file after use!
$token = $_GET['token'] ?? '';
if ($token !== 'brightbath2026setup') {
    die('Unauthorized');
}

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre style='font-family:monospace;background:#1a1a1a;color:#00ff00;padding:20px;'>";
echo "=== THEBRIGHTBATH SETUP ===\n\n";

$action = $_GET['action'] ?? 'status';

if ($action === 'migrate') {
    echo "Running migrations...\n";
    $status = $kernel->call('migrate', ['--force' => true]);
    echo $kernel->output();
    echo "\nMigrate exit code: $status\n";

} elseif ($action === 'seed') {
    echo "Running SiteSettings seeder...\n";
    $kernel->call('db:seed', ['--class' => 'SiteSettingsSeeder', '--force' => true]);
    echo $kernel->output();

    echo "\nRunning ContentItems seeder...\n";
    $kernel->call('db:seed', ['--class' => 'ContentItemsSeeder', '--force' => true]);
    echo $kernel->output();

} elseif ($action === 'cache-clear') {
    echo "Clearing cache...\n";
    $kernel->call('config:clear'); echo $kernel->output();
    $kernel->call('cache:clear');  echo $kernel->output();
    $kernel->call('view:clear');   echo $kernel->output();
    $kernel->call('route:clear');  echo $kernel->output();
    echo "Cache cleared!\n";

} elseif ($action === 'pull') {
    echo "Pulling from GitHub...\n";
    $output = shell_exec('cd ' . dirname(__DIR__) . ' && git pull origin main 2>&1');
    echo $output;

} else {
    echo "Available actions:\n";
    echo "  ?token=brightbath2026setup&action=migrate     - Run migrations\n";
    echo "  ?token=brightbath2026setup&action=seed        - Run seeders\n";
    echo "  ?token=brightbath2026setup&action=cache-clear - Clear all cache\n";
    echo "  ?token=brightbath2026setup&action=pull        - Git pull from GitHub\n";
    echo "\nCurrent status:\n";
    $kernel->call('migrate:status', ['--no-interaction' => true]);
    echo $kernel->output();
}

echo "\n=== DONE ===\n";
echo "</pre>";
