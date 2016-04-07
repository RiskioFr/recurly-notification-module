<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Listener\GuardListener;
use Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent;

class GuardListenerFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : GuardListener
    {
        $isNotificationEvent = $serviceLocator->get(IsNotificationEvent::class);
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');

        $guards = [];
        foreach ($config['guards'] as $service) {
            $guards[] = $serviceLocator->get($service);
        }

        return new GuardListener($isNotificationEvent, $guards);
    }
}
