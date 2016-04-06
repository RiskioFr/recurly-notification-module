<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Listener\AuthenticationListener;

class AuthenticationListenerFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : AuthenticationListener
    {
        $authAdapter = $serviceLocator->get('Riskio\Recurly\NotificationModule\AuthenticationAdapter');
        $logger      = $serviceLocator->get('Riskio\Recurly\NotificationModule\Logger');
        
        return new AuthenticationListener($authAdapter, $logger);
    }
}
