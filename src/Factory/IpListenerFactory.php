<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Listener\IpListener;

class IpListenerFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : IpListener
    {
        $whip   = $serviceLocator->get('Riskio\Recurly\NotificationModule\Whip');
        $logger = $serviceLocator->get('Riskio\Recurly\NotificationModule\Logger');

        return new IpListener($whip, $logger);
    }
}
