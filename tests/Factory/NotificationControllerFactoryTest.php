<?php
namespace Riskio\Recurly\NotificationModuleTest\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Controller\NotificationController;
use Riskio\Recurly\NotificationModule\Factory\NotificationControllerFactory;
use Riskio\Recurly\NotificationModule\Notification\Handler as NotificationHandler;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class NotificationControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $notificationHandler = $this->prophesize(NotificationHandler::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get(NotificationHandler::class)
            ->willReturn($notificationHandler->reveal());

        $controllerPluginManager = $this->prophesize(ServiceLocatorAwareInterface::class);
        $controllerPluginManager
            ->getServiceLocator()
            ->willReturn($container->reveal());

        $factory = new NotificationControllerFactory();

        $controller = $factory($controllerPluginManager->reveal());
        $this->assertInstanceOf(NotificationController::class, $controller);
    }
}