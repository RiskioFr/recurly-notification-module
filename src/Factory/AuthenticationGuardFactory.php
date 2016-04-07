<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Riskio\Recurly\NotificationModule\Guard\AuthenticationGuard;
use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Authentication\Adapter\Http\FileResolver;

class AuthenticationGuardFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : AuthenticationGuard
    {
        $config = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');
        $authAdapter = $this->createAuthenticationAdapterFromConfig(
            $config['guards'][AuthenticationGuard::class]
        );

        $logger = $serviceLocator->get('Riskio\Recurly\NotificationModule\Logger');
        
        return new AuthenticationGuard($authAdapter, $logger);
    }

    private function createAuthenticationAdapterFromConfig(array $config) : AuthAdapter
    {
        $authConfig  = $config['auth_adapter'];
        $authAdapter = new AuthAdapter($authConfig['config']);

        $basicResolver = new FileResolver($authConfig['passwd_file']);
        $authAdapter->setBasicResolver($basicResolver);

        return $authAdapter;
    }
}
