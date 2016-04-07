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
        $config = $serviceManager->get('Riskio\Recurly\NotificationModule\Config');

        /* @var $eventManager \Zend\EventManager\EventManagerInterface */
        $eventManager = $application->getEventManager();

        $notificationConfig = $config['notification'];

        $eventManager->attach($serviceManager->get(ErrorListener::class));
        $eventManager->attach($serviceManager->get(GuardListener::class));

        if (!empty($notificationConfig['listeners']) && is_array($notificationConfig['listeners'])) {
            $notificationHandler = $serviceManager->get(NotificationHandler::class);

            foreach ($notificationConfig['listeners'] as $service) {
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
