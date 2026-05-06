<?php
set_time_limit(300);
@ini_set('memory_limit', '512M');

$token = $_GET['token'] ?? '';
if ($token !== 'brightbath2026setup') { die('Unauthorized'); }

// Auto-detect Laravel root (parent of this script's public_html)
// This script is at .../public_html/run-setup.php
// Laravel root is at .../
$laravelRoot = dirname(__DIR__);

// Verify
if (!file_exists("$laravelRoot/artisan") || !is_dir("$laravelRoot/app")) {
    // Try fallback paths
    $candidates = [
        '/home/u354011138/domains/thebrightbath.com',
        '/home/u354011138/public_html/domains/thebrightbath.com',
        dirname(dirname(__DIR__)),
    ];
    foreach ($candidates as $c) {
        if (file_exists("$c/artisan") && is_dir("$c/app")) {
            $laravelRoot = $c;
            break;
        }
    }
}

$action = $_GET['action'] ?? 'status';

echo "<!DOCTYPE html><html><body><pre style='background:#111;color:#0f0;padding:20px;font-size:13px;'>";
echo "=== THEBRIGHTBATH SETUP ===\n";
echo "Script:       " . __FILE__ . "\n";
echo "Laravel root: $laravelRoot\n";
echo "Action:       $action\n\n";

// ─── STATUS ─────────────────────────────────────────────────────────────────
if ($action === 'status') {
    echo "Directory contents:\n";
    foreach (scandir($laravelRoot) as $f) {
        if ($f === '.' || $f === '..') continue;
        $isDir = is_dir("$laravelRoot/$f");
        echo "  " . ($isDir ? "[DIR] " : "      ") . "$f\n";
    }
    echo "\nKey checks:\n";
    echo "  vendor/:        " . (is_dir("$laravelRoot/vendor") ? "✓" : "✗ MISSING") . "\n";
    echo "  vendor/autoload:" . (file_exists("$laravelRoot/vendor/autoload.php") ? "✓" : "✗") . "\n";
    echo "  .env:           " . (file_exists("$laravelRoot/.env") ? "✓" : "✗ MISSING") . "\n";
    echo "  artisan:        " . (file_exists("$laravelRoot/artisan") ? "✓" : "✗") . "\n";
    echo "  app/:           " . (is_dir("$laravelRoot/app") ? "✓" : "✗") . "\n";
    echo "  bootstrap/:     " . (is_dir("$laravelRoot/bootstrap") ? "✓" : "✗") . "\n";

    echo "\nStorage permissions:\n";
    $storage = "$laravelRoot/storage";
    if (is_dir($storage)) {
        echo "  storage/ writable: " . (is_writable($storage) ? "✓" : "✗") . "\n";
        echo "  storage/ perms: " . substr(sprintf('%o', fileperms($storage)), -4) . "\n";
    }

// ─── CREATE ENV ──────────────────────────────────────────────────────────────
} elseif ($action === 'create-env') {
    $envFile = "$laravelRoot/.env";
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
        if (file_put_contents($envFile, $env)) echo ".env created at $envFile ✓\n";
        else echo "ERROR: Could not write .env to $envFile!\n";
    }

// ─── ENABLE/DISABLE DEBUG ────────────────────────────────────────────────────
} elseif ($action === 'debug-on') {
    $envFile = "$laravelRoot/.env";
    $c = file_get_contents($envFile);
    $c = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=true', $c);
    file_put_contents($envFile, $c);
    echo "APP_DEBUG=true ✓\n";
} elseif ($action === 'debug-off') {
    $envFile = "$laravelRoot/.env";
    $c = file_get_contents($envFile);
    $c = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $c);
    file_put_contents($envFile, $c);
    echo "APP_DEBUG=false ✓\n";

// ─── DB TEST ─────────────────────────────────────────────────────────────────
} elseif ($action === 'db-test') {
    chdir($laravelRoot);
    require "$laravelRoot/vendor/autoload.php";
    $app = require_once "$laravelRoot/bootstrap/app.php";
    try {
        $pdo = $app->make('db')->connection()->getPdo();
        echo "✓ Database connected: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
        echo "Database: " . $app->make('db')->connection()->getDatabaseName() . "\n";
    } catch (\Exception $e) {
        echo "✗ DB ERROR: " . $e->getMessage() . "\n";
    }

// ─── MIGRATE / SEED / CACHE / FINISH (via Laravel kernel) ────────────────────
} elseif (in_array($action, ['migrate','seed','cache-clear','migrate-status','finish'])) {
    if (!file_exists("$laravelRoot/vendor/autoload.php")) {
        die("vendor/ missing!\n");
    }
    if (!file_exists("$laravelRoot/.env")) {
        die(".env missing! Run action=create-env first.\n");
    }
    chdir($laravelRoot);
    require "$laravelRoot/vendor/autoload.php";
    $app = require_once "$laravelRoot/bootstrap/app.php";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    $callKernel = function($cmd, $args = []) use ($kernel) {
        $output = new Symfony\Component\Console\Output\BufferedOutput();
        $code = $kernel->call($cmd, $args, $output);
        echo $output->fetch();
        echo "(exit: $code)\n\n";
    };

    if ($action === 'migrate') {
        echo "Running migrate...\n";
        $callKernel('migrate', ['--force' => true]);

    } elseif ($action === 'migrate-status') {
        $callKernel('migrate:status', []);

    } elseif ($action === 'seed') {
        foreach (['SiteSettingsSeeder', 'ContentItemsSeeder'] as $s) {
            echo "Running $s...\n";
            $callKernel('db:seed', ['--class' => $s, '--force' => true]);
        }

    } elseif ($action === 'cache-clear') {
        foreach (['config:clear','cache:clear','view:clear','route:clear'] as $cmd) {
            echo "Running $cmd...\n";
            $callKernel($cmd, []);
        }

    } elseif ($action === 'finish') {
        echo "=== STEP 1: DB Test ===\n";
        try {
            $pdo = $app->make('db')->connection()->getPdo();
            echo "✓ Connected to " . $app->make('db')->connection()->getDatabaseName() . "\n\n";
        } catch (\Exception $e) {
            die("✗ DB Error: " . $e->getMessage() . "\n");
        }

        echo "=== STEP 2: Migrate ===\n";
        $callKernel('migrate', ['--force' => true]);

        echo "=== STEP 3: Seed SiteSettings ===\n";
        $callKernel('db:seed', ['--class' => 'SiteSettingsSeeder', '--force' => true]);

        echo "=== STEP 4: Seed ContentItems ===\n";
        $callKernel('db:seed', ['--class' => 'ContentItemsSeeder', '--force' => true]);

        echo "=== STEP 5: Clear caches ===\n";
        foreach (['config:clear','cache:clear','view:clear'] as $cmd) {
            $callKernel($cmd, []);
        }

        echo "\n✓✓✓ DONE! Visit https://thebrightbath.com ✓✓✓\n";
    }
}

echo "\n=== END ===\n</pre></body></html>";
