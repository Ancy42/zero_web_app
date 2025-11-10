<?php

return [

    'name' => 'Laravel Web Installer',

    /*
    |--------------------------------------------------------------------------
    | Seeder run permission here
    |--------------------------------------------------------------------------
    */
    'seeder_run' => true,

    /*
    |--------------------------------------------------------------------------
    | minimum php version
    |--------------------------------------------------------------------------
    */
    'minPhpVersion' => '8.2.0',

    /*
    |--------------------------------------------------------------------------
    | Php and server Requirements
    |--------------------------------------------------------------------------
    | php extensions and apache modules requirements
    */
    'php_extensions' => [
        'mysqli',
        'openssl',
        'pdo',
        'mbstring',
        'JSON',
        'cURL',
        'fileinfo',
        'gmp',
        'xml',
        'zip',
        'sodium',
        'bcMath',
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'storage/' => 777,
        'bootstrap/' => 777,
        'app/Providers/' => 775,
        'routes/' => 775,
        'lang/' => 775,
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Form
    |--------------------------------------------------------------------------
    | environment form fields
    |
    */
    'environment_fields' => [
        [
            'APP_NAME' => [
                'rule' => 'required|string|max:50',
                'label' => 'App name',
                'placeholder' => 'e.g: Web-installer',
                'type' => 'text',
            ],
            'APP_URL' => [
                'rule' => 'required|url',
                'label' => 'App base url',
                'placeholder' => 'e.g: http://example.com',
                'type' => 'text',
            ],
            'APP_DEBUG' => [
                'rule' => 'required|string',
                'label' => 'App debug:',
                'placeholder' => 'Choose app debug mode',
                'option' => [true, false],
                'type' => 'radio',
            ],
        ], [
            'DB_CONNECTION' => [
                'rule' => 'required|string|max:50',
                'label' => 'Database Connection',
                'placeholder' => 'Select Databese',
                'type' => 'select',
                'option' => ['mysql', 'sqlite', 'pgsql', 'sqlsrv'],
                'value' => 'mysql',
            ],
            'DB_HOST' => [
                'rule' => 'required|string|max:50',
                'label' => 'Database Host',
                'type' => 'text',
                'placeholder' => 'e.g: 127.0.0.1',
                'value' => '127.0.0.1',
            ],
            'DB_PORT' => [
                'rule' => 'required|numeric',
                'label' => 'Database Port',
                'type' => 'number',
                'placeholder' => 'e.g: 3306',
                'value' => '3306',
            ],
            'DB_DATABASE' => [
                'rule' => 'required|string|max:50',
                'label' => 'Database Name',
                'type' => 'text',
                'placeholder' => 'e.g: web_installer',
            ],
            'DB_USERNAME' => [
                'rule' => 'required|string|max:50',
                'label' => 'Database Username',
                'type' => 'text',
                'placeholder' => 'e.g: root',
            ],
            'DB_PASSWORD' => [
                'rule' => 'nullable|string|max:50',
                'label' => 'Database Password',
                'type' => 'password',
                'placeholder' => 'e.g: **********',
            ],
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Mendetory items which you want to install
    |--------------------------------------------------------------------------
    */
    'need_to_know' => [
        'Codecanyon Purchase Code',
        'Database Name',
        'Database Username',
        'Database Password',
        'Database Hostname',
        'Database Port',
    ],

    /*
    |--------------------------------------------------------------------------
    | Applications User access
    |--------------------------------------------------------------------------
    */
    'users' => [
        'root' => [
            'name' => 'Joynal Abedin',
            'email' => 'abedin.dev@gmail.com',
            'password' => 'secret',
            'email_verified_at' => now(),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Market place validation
    | set a verification code for active from market
    |--------------------------------------------------------------------------
    */
    'product' => 'Ready Grocery', // which product you verify same as supported server
    'productId' => 60154192,
    'admin_role' => 'root',
    'verify_code' => '1ZSrm/fAmszsB+FDhXKh0EdSeERKWkVDSGRqWW51WHBsNG5NMTlYbnFJVjBDd01PMmp6T2FubHFscy9zK2NTTEsrQlVjSnNrZE9CYmRQUXlzWk5NL01ubmlkbnVlZFliWnlOaXRRPT0=',
    'verify_purchase' => true,
    'verify_rules' => [
        'email' => [
            'rule' => 'required|string',
            'label' => 'Your Email',
            'type' => 'email',
            'placeholder' => 'e.g: example@email.com',
        ],
        'purchase_code' => [
            'rule' => 'required|string',
            'label' => 'Purchase Code',
            'type' => 'text',
            'placeholder' => 'e.g: 040afd3f-4cxa-4241-9e70-4gde9e4t674b',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Install commands
    | defind your installation commands
    |--------------------------------------------------------------------------
    */
    'install_commands' => [
        'php artisan migrate:fresh --force',
        'php artisan db:seed --force',
        'php artisan storage:link',
    ],

    /*
    |--------------------------------------------------------------------------
    | Update commands
    | defind your update commands
    |--------------------------------------------------------------------------
    */
    'update_commands' => [
        'composer update'
    ],

    'regular_license' => [
        'link' => '',
        'price' => 0,
    ],
    'extende_license' => [
        'link' => '',
        'price' => 0,
    ],
];
