<?php
set_time_limit(300);
ob_implicit_flush(true);
ob_end_flush();

$token = $_GET['token'] ?? '';
if ($token !== 'brightbath2026setup') { die('Unauthorized'); }

echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:13px;line-height:1.6;'>";
flush();

function run($cmd) {
    echo "$ $cmd\n";
    flush();
    $out = shell_exec($cmd . ' 2>&1');
    echo ($out ?: '(no output)') . "\n";
    flush();
    return $out;
}

echo "=== THEBRIGHTBATH SETUP ===\n\n";

$domainPath = '/home/u354011138/domains/thebrightbath.com';
$repoUrl    = 'https://github.com/hfa0555916989/thebrightbath';
$action     = $_GET['action'] ?? 'status';

// Find correct PHP CLI (not lsphp)
$phpPaths = [
    '/opt/alt/php84/usr/bin/php',
    '/opt/alt/php83/usr/bin/php',
    '/opt/alt/php82/usr/bin/php',
    '/usr/local/bin/php',
    '/usr/bin/php',
];
$php = 'php';
foreach ($phpPaths as $p) {
    if (file_exists($p)) { $php = $p; break; }
}

echo "Domain: $domainPath\n";
echo "PHP CLI: $php\n";
echo "PHP version: " . shell_exec("$php -v 2>&1 | head -1") . "\n";
echo "Action: $action\n\n";
flush();

// ─── DEPLOY ─────────────────────────────────────────────────────────────────
if ($action === 'deploy') {

    echo "=== STEP 1: Git Setup ===\n";
    if (!is_dir("$domainPath/.git")) {
        run("cd $domainPath && git init");
        run("cd $domainPath && git remote add origin $repoUrl");
    } else {
        echo "Git already initialized.\n";
        run("cd $domainPath && git remote set-url origin $repoUrl");
    }
    flush();

    echo "\n=== STEP 2: Pull Code from GitHub ===\n";
    run("cd $domainPath && git fetch --depth=1 origin main");
    run("cd $domainPath && git reset --hard origin/main");
    flush();

    echo "\n=== STEP 3: Create .env ===\n";
    $envFile = "$domainPath/.env";
    if (!file_exists($envFile)) {
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
LOG_DEPRECATIONS_CHANNEL=null
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
MAIL_USERNAME=your_email@thebrightbath.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="your_email@thebrightbath.com"
MAIL_FROM_NAME="${APP_NAME}"

SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
';
        file_put_contents($envFile, $env);
        echo ".env created!\n";
    } else {
        echo ".env already exists - keeping it.\n";
    }
    flush();

    echo "\n=== STEP 4: Composer Install ===\n";
    $composerPaths = ['/usr/local/bin/composer', '/usr/bin/composer', 'composer'];
    $composer = null;
    foreach ($composerPaths as $cp) {
        if (trim(shell_exec("which $cp 2>/dev/null"))) { $composer = $cp; break; }
    }
    if ($composer) {
        echo "Using composer: $composer\n";
        run("cd $domainPath && $composer install --no-dev --optimize-autoloader --no-interaction");
    } else {
        echo "Downloading composer...\n";
        run("curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php");
        run("$php /tmp/composer-setup.php --install-dir=/tmp --filename=composer");
        run("cd $domainPath && $php /tmp/composer install --no-dev --optimize-autoloader --no-interaction");
    }
    flush();

    echo "\n=== STEP 5: Storage Permissions ===\n";
    run("chmod -R 775 $domainPath/storage");
    run("chmod -R 775 $domainPath/bootstrap/cache");
    flush();

    echo "\n=== STEP 6: Migrations ===\n";
    run("cd $domainPath && $php artisan migrate --force");
    flush();

    echo "\n=== STEP 7: Seeders ===\n";
    run("cd $domainPath && $php artisan db:seed --class=SiteSettingsSeeder --force");
    run("cd $domainPath && $php artisan db:seed --class=ContentItemsSeeder --force");
    flush();

    echo "\n=== STEP 8: Clear Cache ===\n";
    run("cd $domainPath && $php artisan config:clear");
    run("cd $domainPath && $php artisan cache:clear");
    run("cd $domainPath && $php artisan view:clear");
    run("cd $domainPath && $php artisan route:clear");
    flush();

    echo "\n✓✓✓ DEPLOY COMPLETE! Visit https://thebrightbath.com ✓✓✓\n";

} elseif ($action === 'status') {
    echo "Files in domain root:\n";
    foreach (scandir($domainPath) as $f) {
        if ($f !== '.' && $f !== '..') {
            echo "  " . (is_dir("$domainPath/$f") ? '[DIR]' : '     ') . " $f\n";
        }
    }
    echo "\nvendor: " . (is_dir("$domainPath/vendor") ? '✓ EXISTS' : '✗ MISSING') . "\n";
    echo ".env:   " . (file_exists("$domainPath/.env") ? '✓ EXISTS' : '✗ MISSING') . "\n";
    echo ".git:   " . (is_dir("$domainPath/.git") ? '✓ EXISTS' : '✗ MISSING') . "\n";
    echo "\n";
    run("$php --version");
    run("which composer || echo 'composer not in PATH'");
    run("which git || echo 'git not found'");

} elseif ($action === 'migrate') {
    run("cd $domainPath && $php artisan migrate --force");
} elseif ($action === 'seed') {
    run("cd $domainPath && $php artisan db:seed --class=SiteSettingsSeeder --force");
    run("cd $domainPath && $php artisan db:seed --class=ContentItemsSeeder --force");
} elseif ($action === 'cache-clear') {
    run("cd $domainPath && $php artisan config:clear");
    run("cd $domainPath && $php artisan cache:clear");
    run("cd $domainPath && $php artisan view:clear");
}

echo "\n=== DONE ===\n</pre>";
