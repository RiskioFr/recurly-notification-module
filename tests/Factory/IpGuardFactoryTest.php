<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Riskio\Recurly\NotificationModule\Factory\IpGuardFactory;
use Riskio\Recurly\NotificationModule\Guard\IpGuard;
use VectorFace\Whip\Whip;

class IpGuardFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $whip   = $this->prophesize(Whip::class);
        $logger = $this->prophesize(LoggerInterface::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn([
                'guards' => [
                    IpGuard::class => [
                        'white_list' => [
                            '74.201.212.175',
                            '64.74.141.175',
                            '75.98.92.102',
                            '74.201.212.0/24',
                            '64.74.141.0/24',
                            '75.98.92.96/28',
                        ],
                    ],
                ],
            ]);
        $container
            ->get('Riskio\Recurly\NotificationModule\Logger')
            ->willReturn($logger->reveal());

        $factory = new IpGuardFactory();

        $listener = $factory($container->reveal());
        $this->assertInstanceOf(IpGuard::class, $listener);
    }
}
