<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Riskio\Recurly\NotificationModule\Exception;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke($serviceLocator) : LoggerInterface
    {
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');
        $logger = $serviceLocator->get($config['notification']['logger']);

        if (! $logger instanceof LoggerInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '`logger` option of Recurly module must be an instance or extend %s class.',
                LoggerInterface::class
            ));
        }

        if ($logger instanceof Logger) {
            $writers = $logger->getWriters()->toArray();
            if (empty($writers)) {
                $logger->addWriter('null');
            }
        }

        return $logger;
    }
}