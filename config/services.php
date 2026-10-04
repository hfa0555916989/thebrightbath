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

];
