<?php
namespace Riskio\Recurly\NotificationModule;

use Riskio\Recurly\NotificationModule\Listener\ErrorListener;
use Riskio\Recurly\NotificationModule\Listener\GuardListener;
use Riskio\Recurly\NotificationModule\Notification\Handler as NotificationHandler;
use Zend\Mvc\MvcEvent;

class Module
{
    const RECURLY_NOTIFICATION_ROUTE = 'recurly/notification';

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        $eventManager = $application->getEventManager();
        $eventManager->attach($serviceManager->get(ErrorListener::class));
        $eventManager->attach($serviceManager->get(GuardListener::class));

        $config = $serviceManager->get('Riskio\Recurly\NotificationModule\Config');
        $listeners = $config['notification']['listeners'] ?? [];

        if (is_array($listeners)) {
            $notificationHandler = $serviceManager->get(NotificationHandler::class);

            foreach ($listeners as $service) {
                $listener = $serviceManager->get($service);
                $notificationHandler->getEventManager()->attach($listener);
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
