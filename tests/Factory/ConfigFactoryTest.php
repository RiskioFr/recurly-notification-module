<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Exception\RuntimeException;
use Riskio\Recurly\NotificationModule\Factory\ConfigFactory;

class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('Riskio\Recurly\ClientModule\Config')->willReturn([
            'notification' => [],
        ]);

        $factory = new ConfigFactory();

        $config = $factory($container->reveal());
        $this->assertInternalType('array', $config);
    }

    public function testCreateServiceWithoutRecurlyConfigKey()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('Riskio\Recurly\ClientModule\Config')->willReturn([]);

        $factory = new ConfigFactory();

        $this->expectException(RuntimeException::class);
        $factory($container->reveal());
    }
}
