<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Exception\RuntimeException;

class ConfigFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : array
    {
        $config = $serviceLocator->get('Riskio\Recurly\ClientModule\Config');

        if (!isset($config['notification'])) {
            throw new RuntimeException('Recurly notification configuration must be defined. Did you copy the config file?');
        }
        
        return $config['notification'];
    }
}
