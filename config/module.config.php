<?php
return [
    'recurly' => [
        'notification' => [
            'logger' => 'Zend\Log\Logger',

            'security' => [
                'ip_checking' => [
                    'enabled' => true,
                    'white_list' => [
                        '74.201.212.175',
                        '64.74.141.175',
                        '75.98.92.102',
                        '74.201.212.0/24',
                        '64.74.141.0/24',
                        '75.98.92.96/28',
                    ],
                ],

                'authentication' => [
                    'enabled' => false,
                    'auth_adapter' => [
                        'config' => [
                            'accept_schemes' => 'basic',
                            'realm'          => 'MyApp Site',
                            'digest_domains' => '/recurly/push',
                            'nonce_timeout'  => 3600,
                        ],
                        'passwd_file'  => __DIR__ . '/../config/passwd.txt',
                    ],
                ],
            ],

            'listeners' => [],
        ],
    ],

    'controllers' => [
        'factories' => [
            'Riskio\Recurly\NotificationModule\Controller\Notification' => 'Riskio\Recurly\NotificationModule\Factory\NotificationControllerFactory',
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'Riskio\Recurly\NotificationModule\Listener\ErrorListener' => 'Riskio\Recurly\NotificationModule\Listener\ErrorListener',
            'Riskio\Recurly\NotificationModule\Notification\Handler'   => 'Riskio\Recurly\NotificationModule\Notification\Handler',
            'Zend\Log\Logger' => 'Zend\Log\Logger',
        ],
        'factories' => [
            'Riskio\Recurly\NotificationModule\AuthenticationAdapter'           => 'Riskio\Recurly\NotificationModule\Factory\AuthenticationAdapterFactory',
            'Riskio\Recurly\NotificationModule\Config'                          => 'Riskio\Recurly\NotificationModule\Factory\ConfigFactory',
            'Riskio\Recurly\NotificationModule\Logger'                          => 'Riskio\Recurly\NotificationModule\Factory\LoggerFactory',
            'Riskio\Recurly\NotificationModule\Listener\AuthenticationListener' => 'Riskio\Recurly\NotificationModule\Factory\AuthenticationListenerFactory',
            'Riskio\Recurly\NotificationModule\Listener\IpListener'             => 'Riskio\Recurly\NotificationModule\Factory\IpListenerFactory',
            'Riskio\Recurly\NotificationModule\Whip'                            => 'Riskio\Recurly\NotificationModule\Factory\WhipFactory',
        ],
    ],

    'router' => [
        'routes' => [
            'recurly' => [
                'type'    => 'Literal',
                'priority' => 1000,
                'options' => [
                    'route'    => '/recurly',
                    'defaults' => [
                        '__NAMESPACE__' => 'Riskio\Recurly\NotificationModule\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ],
                    'constraints' => [
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'notification' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/push',
                            'defaults' => [
                                'controller' => 'notification',
                                'action'     => 'push',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
