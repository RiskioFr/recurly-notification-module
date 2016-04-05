<?php
namespace Riskio\Recurly\NotificationModule\Listener;

use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

class AuthenticationListener extends AbstractAuthorizationListener
{
    /**
     * AuthAdapter
     */
    private $authAdapter;

    public function __construct(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onResult'], -100);
    }

    public function onResult(MvcEvent $event)
    {
        parent::onResult($event);

        if ($event->isError()) {
            $this->logger->info('Failed authentication attempted to push Recurly notification.');

            $event->getResponse()->setStatusCode(HttpResponse::STATUS_CODE_401);
        }
    }

    protected function isGranted(MvcEvent $event) : bool
    {
        $result = $this->authAdapter->authenticate();

        return $result->isValid();
    }
}