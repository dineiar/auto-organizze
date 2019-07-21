<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

return [
    'projectName' => 'Auto-Organizze',
    'projectNamespace' => 'AutoOrganizze',
    'baseHost' => '/auto-organizze',
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'root',
        'pass' => 'admin',
        'db' => 'auto_organizze'
    ]
];
