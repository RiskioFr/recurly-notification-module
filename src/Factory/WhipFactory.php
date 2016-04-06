<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use VectorFace\Whip\Whip;

class WhipFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : Whip
    {
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');

        $recurlyWhitelist = $config['notification']['security']['ip_checking']['white_list'];

        return new Whip(Whip::ALL_METHODS, [
            Whip::REMOTE_ADDR => $recurlyWhitelist,
        ]);
    }
}
