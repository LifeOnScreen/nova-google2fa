<?php

use Lifeonscreen\Google2fa\Models\User2fa;

return [
    /**
     * Disable or enable middleware.
     */
    'enabled' => env('GOOGLE_2FA_ENABLED', true),

    /**
     * Use only if user has configured to do so
     */
    'optional' => env('GOOGLE_2FA_OPTIONAL', false),

    /**
     * Display the secret code as an alternative to using the QR code
     */
    'display_secret_code' => env('GOOGLE_DISPLAY_SECRET_CODE', false),

    /**
     * Apply 2FA auth only on users whose email ends with this domain
     */
    'user_email_domain' => env('GOOGLE_2FA_USER_EMAIL_DOMAIN', ''),

    'models' => [
        /**
         * Change this variable to path to user model.
         */
        'user'    => 'App\User',

        /**
         * Change this if you need a custom connector
         */
        'user2fa' => User2fa::class,
    ],
    'tables' => [
        /**
         * Table in which users are stored.
         */
        'user' => 'users',
    ],

    'recovery_codes' => [
        /**
         * Number of recovery codes that will be generated.
         */
        'count'             => 8,

        /**
         * Number of blocks in each recovery code.
         */
        'blocks'            => 3,

        /**
         * Number of characters in each block in recovery code.
         */
        'chars_in_block'    => 16,

        /**
         * The following algorithms are currently supported:
         *  - PASSWORD_DEFAULT
         *  - PASSWORD_BCRYPT
         *  - PASSWORD_ARGON2I // available from php 7.2
         */
        'hashing_algorithm' => PASSWORD_BCRYPT,
    ],
];
