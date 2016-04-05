<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Riskio\Recurly\NotificationModule\Controller\NotificationController;
use Riskio\Recurly\NotificationModule\Notification\Handler as NotificationHandler;

class NotificationControllerFactory
{
    public function __invoke($serviceLocator) : NotificationController
    {
        $handler = $serviceLocator->getServiceLocator()->get(NotificationHandler::class);

        return new NotificationController($handler);
    }
}