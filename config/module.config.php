<?php

use Laminas\Navigation\Navigation;
use BplNav\Service\Factory\NavManagerFactory;

return [
    'service_manager' => [
        'factories' => [
            Navigation::class => NavManagerFactory::class,
        ],
    ],
    'role_wise_layouts' => [
        'administrator' => 'layout/admin',
        'tscoperator'   => 'layout/admin',
        'tscadmin'      => 'layout/admin',
        'applicant'     => 'layout/layout',
        'user'          => 'layout/layout',
        'guest'         => 'layout/layout',
    ],
];
