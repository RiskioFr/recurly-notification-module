<?php
namespace Riskio\Recurly\NotificationModule;

use Zend\Loader\StandardAutoloader;
use Zend\Mvc\MvcEvent;

class Module
{
    const RECURLY_NOTIFICATION_ROUTE = 'recurly/notification';

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $config = $serviceManager->get('Riskio\Recurly\NotificationModule\Config');

        /* @var $eventManager  \Zend\EventManager\EventManager */
        $eventManager = $application->getEventManager();

        $notificationConfig = $config['notification'];

        if ($notificationConfig['security']['ip_checking']['enabled']) {
            $ipListener = $serviceManager->get('Riskio\Recurly\NotificationModule\Listener\IpListener');
            $eventManager->attach($ipListener);
        }

        if ($notificationConfig['security']['authentication']['enabled']) {
            $authenticationListener = $serviceManager->get('Riskio\Recurly\NotificationModule\Listener\AuthenticationListener');
            $eventManager->attach($authenticationListener);
        }

        $errorListener = $serviceManager->get('Riskio\Recurly\NotificationModule\Listener\ErrorListener');
        $eventManager->attach($errorListener);

        if (!empty($notificationConfig['listeners']) && is_array($notificationConfig['listeners'])) {
            $notificationHandler = $serviceManager->get('Riskio\Recurly\NotificationModule\Notification\Handler');

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

    public function getAutoloaderConfig()
    {
        return array(
            StandardAutoloader::class => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}