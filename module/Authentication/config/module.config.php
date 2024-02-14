<?php
namespace Authentication;

use Laminas\Router\Http\Segment;
return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\WaAuthController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\WaAuthController::class => Factory\WaAuthControllerFactory::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_map' => [
            'layout/auth/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/auth/header' => __DIR__ . '/../view/layout/header.phtml',
            'layout/auth/footer' => __DIR__ . '/../view/layout/footer.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view'
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ]
];
