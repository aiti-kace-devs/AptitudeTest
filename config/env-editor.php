<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Files Config
    |--------------------------------------------------------------------------
    */
    'paths' => [
        'backupDirectory' => storage_path('env-editor'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    */
    'route' => [
        'enable' => true,
        // Prefix url for route Group
        'prefix' => '/admin/env-editor',
        // Routes base name
        'name' => 'env-editor',
        // Middleware(s) applied on route Group
        'middleware' => ['web', 'auth:admin', 'admin.super'],
    ],

    /* ------------------------------------------------------------------------------------------------
    |  Time Format for Views and parsed backups
    | ------------------------------------------------------------------------------------------------
    */
    'timeFormat' => 'd/m/Y H:i:s',

    /* ------------------------------------------------------------------------------------------------
     | Set Views options
     | ------------------------------------------------------------------------------------------------
     | Here you can set The "extends" blade of index.blade.php
    */
    'layout' => 'env-editor::layout',
];
