<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Authentication\Adapter\Http\FileResolver;

class AuthenticationAdapterFactory
{
    public function __invoke(ContainerInterface $serviceLocator) : AuthAdapter
    {
        $config      = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');
        $authConfig  = $config['notification']['security']['authentication']['auth_adapter'];
        $authAdapter = new AuthAdapter($authConfig['config']);

        $basicResolver = new FileResolver($authConfig['passwd_file']);
        $authAdapter->setBasicResolver($basicResolver);

        return $authAdapter;
    }
}
