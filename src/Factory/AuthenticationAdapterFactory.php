<?php
namespace Riskio\Recurly\NotificationModule\Factory;

use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Authentication\Adapter\Http\FileResolver;

class AuthenticationAdapterFactory
{
    public function __invoke($serviceLocator) : AuthAdapter
    {
        $config      = $serviceLocator->get('Riskio\Recurly\NotificationModule\Config');
        $authConfig  = $config['notification']['security']['authentication']['auth_adapter'];
        $authAdapter = new AuthAdapter($authConfig['config']);

        $basicResolver  = new FileResolver();
        $basicResolver->setFile($authConfig['passwd_file']);
        $authAdapter->setBasicResolver($basicResolver);

        return $authAdapter;
    }
}