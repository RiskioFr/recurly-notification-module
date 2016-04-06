<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Factory\WhipFactory;
use VectorFace\Whip\Whip;

class WhipFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config = [
            'notification' => [
                'security' => [
                    'ip_checking' => [
                        'white_list' => [],
                    ],
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('Riskio\Recurly\NotificationModule\Config')
            ->willReturn($config);

        $factory = new WhipFactory();

        $whip = $factory($container->reveal());
        $this->assertInstanceOf(Whip::class, $whip);
    }
}
