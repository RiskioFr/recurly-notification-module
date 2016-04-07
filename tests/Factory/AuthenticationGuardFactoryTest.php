<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Riskio\Recurly\NotificationModule\Factory\AuthenticationGuardFactory;
use Riskio\Recurly\NotificationModule\Guard\AuthenticationGuard;
use Zend\Authentication\Adapter\Http as AuthAdapter;

class AuthenticationGuardFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $logger = $this->prophesize(LoggerInterface::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn([
                'notification' => [
                    'guards' => [
                        AuthenticationGuard::class => [
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
        $container
            ->get('Riskio\Recurly\NotificationModule\Logger')
            ->willReturn($logger->reveal());

        $factory = new AuthenticationGuardFactory();

        $listener = $factory($container->reveal());
        $this->assertInstanceOf(AuthenticationGuard::class, $listener);
    }
}
