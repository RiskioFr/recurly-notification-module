<?php
return [
    'recurly' => [
        'notification' => [
            'logger' => 'Zend\Log\Logger',

            'guards' => [
                'Riskio\Recurly\NotificationModule\Guard\IpGuard' => [
                    'white_list' => [
                        '74.201.212.175',
                        '64.74.141.175',
                        '75.98.92.102',
                        '74.201.212.0/24',
                        '64.74.141.0/24',
                        '75.98.92.96/28',
                    ],
                ],

                'Riskio\Recurly\NotificationModule\Guard\AuthenticationGuard' => [
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
            'Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent' => 'Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent',
            'Zend\Log\Logger' => 'Zend\Log\Logger',
        ],
        'factories' => [
            'Riskio\Recurly\NotificationModule\Config' => 'Riskio\Recurly\NotificationModule\Factory\ConfigFactory',
            'Riskio\Recurly\NotificationModule\Guard\AuthenticationGuard' => 'Riskio\Recurly\NotificationModule\Factory\AuthenticationGuardFactory',
            'Riskio\Recurly\NotificationModule\Guard\IpGuard' => 'Riskio\Recurly\NotificationModule\Factory\IpGuardFactory',
            'Riskio\Recurly\NotificationModule\Listener\GuardListener' => 'Riskio\Recurly\NotificationModule\Factory\GuardListenerFactory',
            'Riskio\Recurly\NotificationModule\Logger' => 'Riskio\Recurly\NotificationModule\Factory\LoggerFactory',
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
