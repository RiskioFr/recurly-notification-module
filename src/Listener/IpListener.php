<?php
namespace Riskio\Recurly\NotificationModule\Listener;

use VectorFace\Whip\Whip;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

class IpListener extends AbstractAuthorizationListener
{
    /**
     * @var Whip
     */
    private $whip;

    public function __construct(Whip $whip)
    {
        $this->whip = $whip;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onResult'], -99);
    }

    public function onResult(MvcEvent $event)
    {
        parent::onResult($event);

        if ($event->isError()) {
            $this->logger->info(sprintf(
                'Unauthorized ip address "%s" attempted to push Recurly notification.',
                $this->whip->getIpAddress()
            ));

            $event->getResponse()->setStatusCode(HttpResponse::STATUS_CODE_403);
        }
    }

    protected function isGranted(MvcEvent $event) : bool
    {
        return false !== $this->whip->getValidIpAddress();
    }
}