<?php
namespace Riskio\Recurly\NotificationModule\Specification;

use Riskio\Recurly\NotificationModule\Module;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;

class IsNotificationEvent
{
    public function isSatisfiedBy(MvcEvent $event) : bool
    {
        $request = $event->getRequest();
        $matchedRouteName = $event->getRouteMatch()->getMatchedRouteName();

        return (
            $request instanceof HttpRequest
            && $matchedRouteName == Module::RECURLY_NOTIFICATION_ROUTE
        );
    }
}
