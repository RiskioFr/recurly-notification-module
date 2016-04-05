<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Factory\IpListenerFactory;
use Riskio\Recurly\NotificationModule\Listener\IpListener;
use VectorFace\Whip\Whip;
use Zend\Log\LoggerInterface;

class IpListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $whip   = $this->prophesize(Whip::class);
        $logger = $this->prophesize(LoggerInterface::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Whip')
            ->willReturn($whip->reveal());
        $container
            ->get('Riskio\Recurly\NotificationModule\Logger')
            ->willReturn($logger->reveal());

        $factory = new IpListenerFactory();

        $listener = $factory($container->reveal());
        $this->assertInstanceOf(IpListener::class, $listener);
    }
}