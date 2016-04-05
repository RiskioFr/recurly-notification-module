<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Exception\InvalidArgumentException;
use Riskio\Recurly\NotificationModule\Factory\LoggerFactory;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;

class LoggerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $logger = $this->prophesize(LoggerInterface::class)->reveal();

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn([
                'notification' => [
                    'logger' => Logger::class,
                ],
            ]);
        $container
            ->get(Logger::class)
            ->willReturn($logger);

        $factory = new LoggerFactory();

        $logger = $factory($container->reveal());
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testCreateServiceWithWrongLoggerService()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn([
                'notification' => [
                    'logger' => 'Foo\Log\Logger',
                ],
            ]);
        $container
            ->get('Foo\Log\Logger')
            ->willReturn(new \stdClass());

        $factory = new LoggerFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory($container->reveal());
    }
}