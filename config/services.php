<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Keys not listed here (resend, postmark, ses, slack...) keep the framework
    | defaults, which read their usual env variables (e.g. RESEND_KEY).
    |
    */

    // Video sessions (https://daily.co). Rooms and access tokens are created
    // through the REST API; the API key never reaches the browser.
    'daily' => [
        'key' => env('DAILY_API_KEY'),
        'api_url' => env('DAILY_API_URL', 'https://api.daily.co/v1'),
    ],

    // Bot protection on register, login and forgot-password (Cloudflare Turnstile:
    // dashboard -> Turnstile -> Add site). Leave both empty to disable the check.
    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    // "Continue with Google" (Google Cloud Console -> APIs & Services -> Credentials
    // -> OAuth client ID, type Web application). Authorized redirect URI:
    // https://<domain>/auth/google/callback. The button shows once both are set.
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    ],

    // "Continue with Apple" (developer.apple.com -> Identifiers -> Services IDs).
    // client_id = the Services ID; Return URL: https://<domain>/auth/apple/callback.
    // private_key = the .p8 file's contents (one line with \n is fine).
    // The button shows once all four are set.
    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'team_id' => env('APPLE_TEAM_ID'),
        'key_id' => env('APPLE_KEY_ID'),
        'private_key' => env('APPLE_PRIVATE_KEY'),
    ],

];
