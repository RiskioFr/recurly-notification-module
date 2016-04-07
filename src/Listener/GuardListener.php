<?php
namespace Riskio\Recurly\NotificationModule\Listener;

use Riskio\Recurly\NotificationModule\Exception;
use Riskio\Recurly\NotificationModule\Guard\GuardInterface;
use Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class GuardListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    private $isNotificationEvent;

    private $guards = [];

    public function __construct(IsNotificationEvent $isNotificationEvent, array $guards = [])
    {
        $this->guards = $guards;
        $this->isNotificationEvent = $isNotificationEvent;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onResult'], -100);
    }

    public function onResult(MvcEvent $event)
    {
        if (
            $this->isNotificationEvent->isSatisfiedBy($event)
            && !$this->isGranted($event)
        ) {
            $event->setError('unauthorized');
            $event->setParam('exception', Exception\UnauthorizedException::create());
            $event->stopPropagation(true);

            $application = $event->getApplication();
            $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
    }

    private function isGranted(MvcEvent $event) : bool
    {
        foreach ($this->guards as $guard) {
            if (!$guard instanceof GuardInterface) {
                continue;
            }

            if (!$guard->isGranted($event)) {
                $guard->onFailure($event);
                return false;
            } else {
                $guard->onSuccess($event);
            }
        }

        return true;
    }
}
