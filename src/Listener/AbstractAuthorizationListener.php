<?php
namespace Riskio\Recurly\NotificationModule\Listener;

use Riskio\Recurly\NotificationModule\Exception;
use Riskio\Recurly\NotificationModule\Module;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;

abstract class AbstractAuthorizationListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function onResult(MvcEvent $event)
    {
        $request = $event->getRequest();
        if (!$request instanceof HttpRequest) {
            return;
        }

        $matchedRouteName = $event->getRouteMatch()->getMatchedRouteName();
        if ($matchedRouteName != Module::RECURLY_NOTIFICATION_ROUTE) {
            return;
        }

        if ($this->isGranted($event)) {
            return;
        }

        $event->setError('unauthorized');
        $event->setParam('exception', Exception\UnauthorizedException::create());
        $event->stopPropagation(true);

        $application = $event->getApplication();
        $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
    }

    abstract protected function isGranted(MvcEvent $event) : bool;
}
