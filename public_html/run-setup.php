<?php
set_time_limit(300);
@ini_set('memory_limit', '512M');

$token = $_GET['token'] ?? '';
if ($token !== 'brightbath2026setup') { die('Unauthorized'); }

$domainPath = '/home/u354011138/domains/thebrightbath.com';
$action = $_GET['action'] ?? 'status';

echo "<!DOCTYPE html><html><body><pre style='background:#111;color:#0f0;padding:20px;font-size:13px;'>";
echo "=== THEBRIGHTBATH SETUP (no-shell mode) ===\n";
echo "Domain: $domainPath\n";
echo "Action: $action\n\n";

// ─── STATUS ─────────────────────────────────────────────────────────────────
if ($action === 'status') {
    echo "Directory contents:\n";
    foreach (scandir($domainPath) as $f) {
        if ($f === '.' || $f === '..') continue;
        $isDir = is_dir("$domainPath/$f");
        echo "  " . ($isDir ? "[DIR] " : "      ") . "$f\n";
    }
    echo "\nKey checks:\n";
    echo "  vendor/:        " . (is_dir("$domainPath/vendor") ? "✓" : "✗ MISSING - upload vendor.zip!") . "\n";
    echo "  vendor/autoload:" . (file_exists("$domainPath/vendor/autoload.php") ? "✓" : "✗") . "\n";
    echo "  .env:           " . (file_exists("$domainPath/.env") ? "✓" : "✗ MISSING") . "\n";
    echo "  artisan:        " . (file_exists("$domainPath/artisan") ? "✓" : "✗ MISSING") . "\n";
    echo "  app/:           " . (is_dir("$domainPath/app") ? "✓" : "✗ MISSING") . "\n";
    echo "  bootstrap/:     " . (is_dir("$domainPath/bootstrap") ? "✓" : "✗ MISSING") . "\n";

// ─── CREATE ENV ──────────────────────────────────────────────────────────────
} elseif ($action === 'create-env') {
    $envFile = "$domainPath/.env";
    if (file_exists($envFile)) {
        echo ".env already exists.\n";
    } else {
        $env = 'APP_NAME="Bright Path Portal"
APP_ENV=production
APP_KEY=base64:XYXLD85OE72qQIohCkNRJ+3O+gCfu4f54OrqxuJYHlc=
APP_DEBUG=false
APP_TIMEZONE=Asia/Riyadh
APP_URL=https://thebrightbath.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u354011138_bright
DB_USERNAME=u354011138_bright
DB_PASSWORD=Hfa@900hfahfa

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@thebrightbath.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@thebrightbath.com"
MAIL_FROM_NAME="${APP_NAME}"

SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
';
        if (file_put_contents($envFile, $env)) echo ".env created ✓\n";
        else echo "ERROR: Could not write .env!\n";
    }

// ─── ENABLE DEBUG ────────────────────────────────────────────────────────────
} elseif ($action === 'debug-on') {
    $envFile = "$domainPath/.env";
    if (!file_exists($envFile)) { echo "No .env yet.\n"; }
    else {
        $c = file_get_contents($envFile);
        $c = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=true', $c);
        file_put_contents($envFile, $c);
        echo "APP_DEBUG=true ✓ (revert with action=debug-off)\n";
    }
} elseif ($action === 'debug-off') {
    $envFile = "$domainPath/.env";
    $c = file_get_contents($envFile);
    $c = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $c);
    file_put_contents($envFile, $c);
    echo "APP_DEBUG=false ✓\n";

// ─── MIGRATE (via Laravel kernel) ────────────────────────────────────────────
} elseif ($action === 'migrate') {
    if (!file_exists("$domainPath/vendor/autoload.php")) {
        die("vendor/ missing! Upload vendor folder first.\n");
    }
    chdir($domainPath);
    require "$domainPath/vendor/autoload.php";
    $app = require_once "$domainPath/bootstrap/app.php";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "Running migrate...\n";
    $output = new Symfony\Component\Console\Output\BufferedOutput();
    $code = $kernel->call('migrate', ['--force' => true], $output);
    echo $output->fetch();
    echo "\nExit code: $code\n";

// ─── SEED ────────────────────────────────────────────────────────────────────
} elseif ($action === 'seed') {
    chdir($domainPath);
    require "$domainPath/vendor/autoload.php";
    $app = require_once "$domainPath/bootstrap/app.php";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    foreach (['SiteSettingsSeeder', 'ContentItemsSeeder'] as $seeder) {
        echo "Running $seeder...\n";
        $output = new Symfony\Component\Console\Output\BufferedOutput();
        $code = $kernel->call('db:seed', ['--class' => $seeder, '--force' => true], $output);
        echo $output->fetch();
        echo "Exit: $code\n\n";
    }

// ─── CACHE CLEAR ─────────────────────────────────────────────────────────────
} elseif ($action === 'cache-clear') {
    chdir($domainPath);
    require "$domainPath/vendor/autoload.php";
    $app = require_once "$domainPath/bootstrap/app.php";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    foreach (['config:clear','cache:clear','view:clear','route:clear'] as $cmd) {
        echo "Running $cmd...\n";
        $output = new Symfony\Component\Console\Output\BufferedOutput();
        $kernel->call($cmd, [], $output);
        echo $output->fetch() . "\n";
    }

// ─── MIGRATE STATUS ──────────────────────────────────────────────────────────
} elseif ($action === 'migrate-status') {
    chdir($domainPath);
    require "$domainPath/vendor/autoload.php";
    $app = require_once "$domainPath/bootstrap/app.php";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $output = new Symfony\Component\Console\Output\BufferedOutput();
    $kernel->call('migrate:status', [], $output);
    echo $output->fetch();

// ─── DB TEST ─────────────────────────────────────────────────────────────────
} elseif ($action === 'db-test') {
    chdir($domainPath);
    require "$domainPath/vendor/autoload.php";
    $app = require_once "$domainPath/bootstrap/app.php";
    try {
        $pdo = $app->make('db')->connection()->getPdo();
        echo "✓ Database connected: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    } catch (\Exception $e) {
        echo "✗ DB ERROR: " . $e->getMessage() . "\n";
    }

// ─── ALL IN ONE ──────────────────────────────────────────────────────────────
} elseif ($action === 'finish') {
    if (!file_exists("$domainPath/.env")) {
        echo "No .env! Run action=create-env first.\n";
    }
    if (!file_exists("$domainPath/vendor/autoload.php")) {
        die("\nvendor/ missing! Upload vendor.zip first.\n");
    }
    chdir($domainPath);
    require "$domainPath/vendor/autoload.php";
    $app = require_once "$domainPath/bootstrap/app.php";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "=== STEP 1: Test DB ===\n";
    try {
        $pdo = $app->make('db')->connection()->getPdo();
        echo "✓ Connected\n\n";
    } catch (\Exception $e) {
        die("✗ DB Error: " . $e->getMessage() . "\n");
    }

    echo "=== STEP 2: Migrate ===\n";
    $out = new Symfony\Component\Console\Output\BufferedOutput();
    $kernel->call('migrate', ['--force' => true], $out);
    echo $out->fetch() . "\n";

    echo "=== STEP 3: Seed ===\n";
    foreach (['SiteSettingsSeeder', 'ContentItemsSeeder'] as $s) {
        echo "-- $s\n";
        $out = new Symfony\Component\Console\Output\BufferedOutput();
        $kernel->call('db:seed', ['--class' => $s, '--force' => true], $out);
        echo $out->fetch() . "\n";
    }

    echo "=== STEP 4: Cache clear ===\n";
    foreach (['config:clear','cache:clear','view:clear'] as $cmd) {
        $out = new Symfony\Component\Console\Output\BufferedOutput();
        $kernel->call($cmd, [], $out);
        echo $out->fetch();
    }

    echo "\n✓✓✓ DONE! Visit https://thebrightbath.com ✓✓✓\n";
}

echo "\n=== END ===\n</pre></body></html>";
