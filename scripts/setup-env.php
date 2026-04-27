<?php
if (!file_exists('.env')) {
    copy('.env.example', '.env');
}

$env = file_get_contents('.env');
if (!preg_match('/^APP_KEY=base64:/m', $env)) {
    $key = 'base64:' . base64_encode(random_bytes(32));
    $env = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $key, $env);
    file_put_contents('.env', $env);
}
