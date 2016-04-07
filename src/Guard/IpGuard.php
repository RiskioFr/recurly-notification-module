<?php
namespace Riskio\Recurly\NotificationModule\Guard;

use VectorFace\Whip\Whip;
use Zend\Http\Response as HttpResponse;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class IpGuard implements GuardInterface
{
    /**
     * @var Whip
     */
    private $whip;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Whip $whip, LoggerInterface $logger)
    {
        $this->whip   = $whip;
        $this->logger = $logger;
    }

    public function isGranted(MvcEvent $event) : bool
    {
        return false !== $this->whip->getValidIpAddress();
    }

    public function onSuccess(MvcEvent $event)
    {

    }

    public function onFailure(MvcEvent $event)
    {
        $this->logger->info(sprintf(
            'Unauthorized ip address "%s" attempted to push Recurly notification.',
            $this->whip->getIpAddress()
        ));

        $event->getResponse()->setStatusCode(HttpResponse::STATUS_CODE_403);
    }
}
