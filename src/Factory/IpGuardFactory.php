<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Guard\IpGuard;
use Vectorface\Whip\Whip;

class IpGuardFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : IpGuard
    {
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');
        $whip = $this->createWhipFromConfig($config['notification']['guards'][IpGuard::class]);

        $logger = $serviceLocator->get('Riskio\Recurly\NotificationModule\Logger');

        return new IpGuard($whip, $logger);
    }

    private function createWhipFromConfig(array $config) : Whip
    {
        return new Whip(Whip::ALL_METHODS, [
            Whip::REMOTE_ADDR => $config['white_list'],
        ]);
    }
}
