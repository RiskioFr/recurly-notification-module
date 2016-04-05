<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Riskio\Recurly\NotificationModule\Listener\IpListener;

class IpListenerFactory
{
    public function __invoke($serviceLocator) : IpListener
    {
        $whip = $serviceLocator->get('Riskio\Recurly\NotificationModule\Whip');

        $listener = new IpListener($whip);

        $logger = $serviceLocator->get('Riskio\Recurly\NotificationModule\Logger');
        $listener->setLogger($logger);

        return $listener;
    }
}