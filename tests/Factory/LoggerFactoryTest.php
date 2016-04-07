<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Riskio\Recurly\NotificationModule\Exception\InvalidArgumentException;
use Riskio\Recurly\NotificationModule\Factory\LoggerFactory;
use Zend\Log\Logger;

class LoggerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn([
                'logger' => Logger::class,
            ]);
        $container
            ->get(Logger::class)
            ->willReturn(new Logger());

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
                'logger' => 'Foo\Log\Logger',
            ]);
        $container
            ->get('Foo\Log\Logger')
            ->willReturn(new \stdClass());

        $factory = new LoggerFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory($container->reveal());
    }
}
