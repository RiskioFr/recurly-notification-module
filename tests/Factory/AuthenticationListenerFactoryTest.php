<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Factory\AuthenticationListenerFactory;
use Riskio\Recurly\NotificationModule\Listener\AuthenticationListener;
use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Log\LoggerInterface;

class AuthenticationListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $authAdapter = $this->prophesize(AuthAdapter::class);
        $logger = $this->prophesize(LoggerInterface::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\AuthenticationAdapter')
            ->willReturn($authAdapter->reveal());
        $container
            ->get('Riskio\Recurly\NotificationModule\Logger')
            ->willReturn($logger->reveal());

        $factory = new AuthenticationListenerFactory();

        $listener = $factory($container->reveal());
        $this->assertInstanceOf(AuthenticationListener::class, $listener);
    }
}