<?php

return [
    'model' => Betterde\Permission\Models\Permission::class,
    'table' => 'permissions',
    'cache' => [
        'enable' => true,
        'prefix' => 'betterde',
        'ttl' => 60,
        'database' => 'cache'
    ]
];