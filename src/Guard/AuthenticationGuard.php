<?php
namespace Riskio\Recurly\NotificationModule\Guard;

use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Http\Response as HttpResponse;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class AuthenticationGuard implements GuardInterface
{
    /**
     * @var AuthAdapter
     */
    private $authAdapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(AuthAdapter $authAdapter, LoggerInterface $logger)
    {
        $this->authAdapter = $authAdapter;
        $this->logger      = $logger;
    }

    public function isGranted(MvcEvent $event) : bool
    {
        $result = $this->authAdapter->authenticate();

        return $result->isValid();
    }

    public function onSuccess(MvcEvent $event)
    {

    }

    public function onFailure(MvcEvent $event)
    {
        $this->logger->info('Failed authentication attempted to push Recurly notification.');

        $event->getResponse()->setStatusCode(HttpResponse::STATUS_CODE_401);
    }
}
