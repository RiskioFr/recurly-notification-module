<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Riskio\Recurly\NotificationModule\Listener\AuthenticationListener;

class AuthenticationListenerFactory
{
    public function __invoke($serviceLocator) : AuthenticationListener
    {
        $authAdapter = $serviceLocator->get('Riskio\Recurly\NotificationModule\AuthenticationAdapter');
        
        $listener = new AuthenticationListener($authAdapter);
        
        $logger = $serviceLocator->get('Riskio\Recurly\NotificationModule\Logger');
        $listener->setLogger($logger);
        
        return $listener;
    }
}