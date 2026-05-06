<?php
$token = $_GET['token'] ?? '';
if ($token !== 'brightbath2026setup') { die('Unauthorized'); }

echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:13px;line-height:1.6;'>";
echo "=== THEBRIGHTBATH SETUP ===\n\n";

$domainPath = '/home/u354011138/domains/thebrightbath.com';
$repoUrl    = 'https://github.com/hfa0555916989/thebrightbath';
$php        = PHP_BINARY;
$action     = $_GET['action'] ?? 'status';

echo "Domain path: $domainPath\n";
echo "PHP: $php\n";
echo "Action: $action\n\n";

// ─── DEPLOY: clone/pull all Laravel files ───────────────────────────────────
if ($action === 'deploy') {

    echo "=== STEP 1: Setup Git in domain directory ===\n";

    // Init git if not already
    if (!is_dir("$domainPath/.git")) {
        echo shell_exec("cd $domainPath && git init 2>&1") . "\n";
        echo shell_exec("cd $domainPath && git remote add origin $repoUrl 2>&1") . "\n";
    } else {
        echo "Git already initialized.\n";
        // Make sure remote is correct
        shell_exec("cd $domainPath && git remote set-url origin $repoUrl 2>&1");
    }

    echo "\n=== STEP 2: Pull latest code from GitHub ===\n";
    echo shell_exec("cd $domainPath && git fetch --depth=1 origin main 2>&1") . "\n";
    echo shell_exec("cd $domainPath && git checkout -f origin/main 2>&1") . "\n";
    echo shell_exec("cd $domainPath && git pull origin main 2>&1") . "\n";

    echo "\n=== STEP 3: Create .env file ===\n";
    $envFile = "$domainPath/.env";
    if (!file_exists($envFile)) {
        $envContent = 'APP_NAME="Bright Path Portal"
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
        file_put_contents($envFile, $envContent);
        echo ".env file created!\n";
    } else {
        echo ".env already exists.\n";
    }

    echo "\n=== STEP 4: Install Composer dependencies ===\n";
    // Try different composer paths
    $composerPaths = ['composer', '/usr/local/bin/composer', '/usr/bin/composer'];
    $composer = null;
    foreach ($composerPaths as $cp) {
        $v = shell_exec("$cp --version 2>&1");
        if ($v && str_contains($v, 'Composer')) {
            $composer = $cp;
            echo "Found composer: $cp\n";
            break;
        }
    }

    if ($composer) {
        echo shell_exec("cd $domainPath && $composer install --no-dev --optimize-autoloader --no-interaction 2>&1") . "\n";
    } else {
        echo "WARNING: Composer not found. Trying to download it...\n";
        echo shell_exec("cd $domainPath && curl -sS https://getcomposer.org/installer | $php && $php composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1") . "\n";
    }

    echo "\n=== STEP 5: Fix storage permissions ===\n";
    echo shell_exec("chmod -R 775 $domainPath/storage $domainPath/bootstrap/cache 2>&1") . "\n";

    echo "\n=== STEP 6: Run Migrations ===\n";
    echo shell_exec("cd $domainPath && $php artisan migrate --force 2>&1") . "\n";

    echo "\n=== STEP 7: Run Seeders ===\n";
    echo shell_exec("cd $domainPath && $php artisan db:seed --class=SiteSettingsSeeder --force 2>&1") . "\n";
    echo shell_exec("cd $domainPath && $php artisan db:seed --class=ContentItemsSeeder --force 2>&1") . "\n";

    echo "\n=== STEP 8: Clear Cache ===\n";
    echo shell_exec("cd $domainPath && $php artisan config:clear 2>&1") . "\n";
    echo shell_exec("cd $domainPath && $php artisan cache:clear 2>&1") . "\n";
    echo shell_exec("cd $domainPath && $php artisan view:clear 2>&1") . "\n";
    echo shell_exec("cd $domainPath && $php artisan route:clear 2>&1") . "\n";

    echo "\n✓ DEPLOY COMPLETE! Visit https://thebrightbath.com\n";

} elseif ($action === 'status') {
    echo "Directory contents:\n";
    $files = scandir($domainPath);
    foreach ($files as $f) {
        if ($f !== '.' && $f !== '..') {
            $type = is_dir("$domainPath/$f") ? '[DIR]' : '[FILE]';
            echo "  $type $f\n";
        }
    }
    echo "\nvendor exists: " . (is_dir("$domainPath/vendor") ? 'YES' : 'NO') . "\n";
    echo ".env exists: " . (file_exists("$domainPath/.env") ? 'YES' : 'NO') . "\n";
    echo ".git exists: " . (is_dir("$domainPath/.git") ? 'YES' : 'NO') . "\n";

} elseif ($action === 'migrate') {
    echo shell_exec("cd $domainPath && $php artisan migrate --force 2>&1");
} elseif ($action === 'seed') {
    echo shell_exec("cd $domainPath && $php artisan db:seed --class=SiteSettingsSeeder --force 2>&1");
    echo shell_exec("cd $domainPath && $php artisan db:seed --class=ContentItemsSeeder --force 2>&1");
} elseif ($action === 'cache-clear') {
    echo shell_exec("cd $domainPath && $php artisan config:clear 2>&1");
    echo shell_exec("cd $domainPath && $php artisan cache:clear 2>&1");
    echo shell_exec("cd $domainPath && $php artisan view:clear 2>&1");
} elseif ($action === 'pull') {
    echo shell_exec("cd $domainPath && git pull origin main 2>&1");
}

echo "\n=== DONE ===\n</pre>";
