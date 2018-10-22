<?php

return [
    /**
     * Disable or enable middleware.
     */
    'enabled' => env('GOOGLE_2FA_ENABLED', true),

    'models' => [
        /**
         * Change this variable to your User model.
         */
        'user' => 'App\User',
    ],
    'tables' => [
        /**
         * Table in witch users are stored.
         */
        'user' => 'users',
    ],

    'recovery_codes' => [
        /**
         * Number of recovery codes that will be generated.
         */
        'count'          => 8,

        /**
         * Number of blocks in each recovery code.
         */
        'blocks'         => 3,

        /**
         * Number of characters in each block in recovery code.
         */
        'chars_in_block' => 16,
    ],
];