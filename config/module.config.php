<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    'service_manager' => [
        'factories' => [
            \Laminas\Navigation\Navigation::class => \BplNav\Service\Factory\NavManagerFactory::class,
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
