<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Riskio\Recurly\NotificationModule\Exception\RuntimeException;

class ConfigFactory
{
    public function __invoke($serviceLocator) : array
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['recurly'])) {
            throw new RuntimeException('Recurly configuration must be defined. Did you copy the config file?');
        }
        
        return $config['recurly'];
    }
}