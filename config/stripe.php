<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Keys and Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration values are used for Stripe payment gateway integration.
    | Values are loaded from the environment configuration (.env) file.
    |
    */

    'key' => env('STRIPE_KEY'),

    'secret' => env('STRIPE_SECRET'),

    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    'currency' => env('STRIPE_CURRENCY', 'pen'),
];
