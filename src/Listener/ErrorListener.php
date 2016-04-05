<?php
namespace Riskio\Recurly\NotificationModule\Listener;

use Riskio\Recurly\NotificationModule\Exception\UnauthorizedExceptionInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

class ErrorListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onError']);
    }

    public function onError(MvcEvent $event)
    {
        // Do nothing if no error or if response is not HTTP response
        if (!($exception = $event->getParam('exception') instanceof UnauthorizedExceptionInterface)
            || !($response = $event->getResponse() instanceof HttpResponse)
        ) {
            return;
        }

        $response = $event->getResponse() ?: new HttpResponse();

        $event->setResponse($response);
        $event->setResult($response);
    }
}