<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Factory\AuthenticationAdapterFactory;
use Zend\Authentication\Adapter\Http as AuthAdapter;

class AuthenticationAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn([
                'notification' => [
                    'security' => [
                        'authentication' => [
                            'auth_adapter' => [
                                'config' => [
                                    'accept_schemes' => 'basic',
                                    'realm'          => 'MyApp Site',
                                ],
                                'passwd_file'  => __DIR__ . '/_files/passwd.txt',
                            ],
                        ],
                    ],
                ],
            ]);

        $factory = new AuthenticationAdapterFactory();

        $adapter = $factory($container->reveal());
        $this->assertInstanceOf(AuthAdapter::class, $adapter);
    }
}
