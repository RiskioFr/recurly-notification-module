<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Listener\GuardListener;

class GuardListenerFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : GuardListener
    {
        $isNotificationEvent = $serviceLocator->get('Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent');
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');

        $guards = [];
        foreach ($config['guards'] as $service) {
            $guards[] = $serviceLocator->get($service);
        }

        return new GuardListener($isNotificationEvent, $guards);
    }
}
