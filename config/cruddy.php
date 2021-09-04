<?php

return [

    /**
     * This is the location of the folder that contains all stubs Cruddy should
     * use when creating all forms. Do not include trailing slash.
     *
     */
    'stubs_folder' => 'stubs/cruddy',

    /**
     * This flag indicates if the frontend views should be created. Set this variable to false
     * if your resource(s) should function as an API endpoint without a UI.
     *
     */
    'needs_ui' => true,

    /**
     * This flag indicates if the resource being created is an API and only needs
     * the standard CRUD API endpoints. This will override frontend_scaffolding option.
     *
     */
    'is_api' => false,

    /**
     * The frontend scaffolding used for all views.
     *
     */
    'frontend_scaffolding' => env('CRUDDY_FRONTEND_SCAFFOLDING', 'default'),

    /**
     * This is the destination folder within the resources folder where Cruddy should place vue files.
     *
     */
    'vue_folder' => 'js/components',

    /**
     * This is the file where Cruddy should place the vue imports.
     *
     */
    'vue_import_file' => 'resources/js/bootstrap.js',

    /**
     * The default validation rules for all columns in the CuddyBlueprint. These validation rules
     * are written to the CruddyRequest.
     *
     */
    // Note: Need to update these defaults to reasonable values.
    'validation_defaults' => [
        'bigInteger' => 'required|integer|min:1', // Note: Also "unsignedBigInteger".
        'binary' => 'string',
        'boolean' => 'boolean',
        'char' => 'char',
        'dateTimeTz' => 'dateTimeTz',
        'dateTime' => 'dateTime',
        'decimal' => 'decimal', // Note: Also "unsignedDecimal".
        'double' => 'double',
        'float' => 'float',
        'foreignId' => 'foreignId',
        'integer' => 'integer', // Note: Also "unsignedInteger".
        'ipAddress' => 'ipAddress',
        'json' => 'json',
        'jsonb' => 'jsonb',
        'lineString' => 'lineString',
        'macAddress' => 'macAddress',
        'mediumInteger' => 'mediumInteger', // Note: Also "mediumIncrements", "unsignedMediumInteger".
        'mediumText' => 'mediumText',
        'morphs' => 'morphs',
        'multiLineString' => 'multiLineString',
        'multiPoint' => 'multiPoint',
        'multiPolygon' => 'multiPolygon',
        'nullableMorphs' => 'nullableMorphs',
        'nullableUuidMorphs' => 'nullableUuidMorphs',
        'point' => 'point',
        'polygon' => 'polygon',
        'set' => 'set',
        'rememberToken' => 'rememberToken',
        'string' => 'nullable|string',
        'text' => 'text',
        'timeTz' => 'timeTz',
        'time' => 'time',
        'timestampTz' => 'timestampTz', // Note: Also "softDeletesTz", "timestampsTz".
        'timestamp' => 'timestamp', // Note: Also "softDeletes", "timestamps".
        'tinyInteger' => 'integer', // Note: Also "tinyIncrements", "unsignedTinyInteger".
        'tinyText' => 'tinyText',
        'smallInteger' => 'smallInteger', // Note: Also "smallIncrements", "unsignedSmallInteger".
        'uuid' => 'uuid',
        'uuidMorphs' => 'uuidMorphs',
        'year' => 'year',
    ],

    /**
     * The default input types for all columns in the CuddyBlueprint. These input types
     * are written to the view files.
     *
     */
    // Note: Need to update these defaults to reasonable values.
    'input_defaults' => [
        'bigInteger' => 'number',
        'unsignedBigInteger' => 'number',
        'binary' => 'text',
        'boolean' => 'checkbox',
        'char' => 'text',
        'dateTimeTz' => 'date',
        'dateTime' => 'date',
        'decimal' => 'number',
        'unsignedDecimal' => 'number',
        'double' => 'number',
        'float' => 'number',
        'foreignId' => 'number',
        'integer' => 'number',
        'unsignedInteger' => 'number',
        'ipAddress' => 'text',
        'json' => 'text',
        'jsonb' => 'text',
        'lineString' => 'text',
        'macAddress' => 'text',
        'mediumInteger' => 'number',
        'mediumIncrements' => 'number',
        'unsignedMediumInteger' => 'number',
        'mediumText' => 'text',
        'morphs' => 'text',
        'multiLineString' => 'text',
        'multiPoint' => 'text',
        'multiPolygon' => 'text',
        'nullableMorphs' => 'text',
        'nullableUuidMorphs' => 'text',
        'point' => 'text',
        'polygon' => 'text',
        'set' => 'text',
        'rememberToken' => 'text',
        'string' => 'text',
        'text' => 'text',
        'timeTz' => 'text',
        'time' => 'text',
        'timestampTz' => 'text',
        'softDeletesTz' => 'text',
        'timestampsTz' => 'text',
        'timestamp' => 'text',
        'softDeletes' => 'text',
        'timestamps' => 'text',
        'tinyInteger' => 'checkbox',
        'tinyIncrements' => 'number',
        'unsignedTinyInteger' => 'number',
        'tinyText' => 'text',
        'smallInteger' => 'number',
        'smallIncrements' => 'number',
        'unsignedSmallInteger' => 'number',
        'uuid' => 'text',
        'uuidMorphs' => 'text',
        'year' => 'text',
        'submit' => 'submit' // Needed for Cruddy. Is not part of Laravel.
    ],

    /**
     * The new database connection to use for Cruddy. Update this to use whatever DB connection you need when running Cruddy.
     *
     */
    'database' => [
        'connections' => [
            'cruddy' => [
                'driver' => 'mysql',
                'url' => env('DATABASE_URL'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'forge'),
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
        ]
    ]
];
