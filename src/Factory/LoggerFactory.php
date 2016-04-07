<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Riskio\Recurly\NotificationModule\Exception;
use Zend\Log\LoggerInterface as ZendLoggerInterface;
use Zend\Log\PsrLoggerAdapter;

class LoggerFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : LoggerInterface
    {
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');
        $logger = $serviceLocator->get($config['logger']);

        if ($logger instanceof ZendLoggerInterface) {
            $logger = new PsrLoggerAdapter($logger);
        }

        if (! $logger instanceof LoggerInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '`logger` option of Recurly module must be an instance or extend %s class.',
                LoggerInterface::class
            ));
        }

        return $logger;
    }
}
