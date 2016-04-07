<?php
namespace Riskio\Recurly\NotificationModuleTest\Listener;

use Riskio\Recurly\NotificationModule\Guard\GuardInterface;
use Riskio\Recurly\NotificationModule\Listener\GuardListener;
use Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent;
use Prophecy\Argument;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;

class GuardListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testAttachEvent()
    {
        $isNotificationEvent = $this->prophesize(IsNotificationEvent::class);

        $listener = new GuardListener($isNotificationEvent->reveal());

        $eventManager = $this->prophesize(EventManagerInterface::class);
        $eventManager
            ->attach(MvcEvent::EVENT_ROUTE, Argument::type('array'), Argument::type('int'))
            ->shouldBeCalled();

        $listener->attach($eventManager->reveal());
    }

    public function testOnResult_GivenNotificationEventAndNoGuards_ShouldDoNothing()
    {
        $event = new MvcEvent();
        $expectedEvent = clone $event;

        $isNotificationEvent = $this->prophesize(IsNotificationEvent::class);
        $isNotificationEvent->isSatisfiedBy($event)->willReturn(true);

        $listener = new GuardListener($isNotificationEvent->reveal());

        $listener->onResult($event);

        $this->assertEquals($expectedEvent, $event);
    }

    public function testOnResult_GivenInvalidNotificationEventAndNoGuards_ShouldDoNothing()
    {
        $event = new MvcEvent();
        $expectedEvent = clone $event;

        $isNotificationEvent = $this->prophesize(IsNotificationEvent::class);
        $isNotificationEvent->isSatisfiedBy($event)->willReturn(false);

        $listener = new GuardListener($isNotificationEvent->reveal());

        $listener->onResult($event);

        $this->assertEquals($expectedEvent, $event);
    }

    public function testOnResult_GivenNotificationEventAndNotGrantedGuard_ShouldAddErrorToEvent()
    {
        $event = new MvcEvent();

        $response = new HttpResponse();
        $event->setResponse($response);

        $isNotificationEvent = $this->prophesize(IsNotificationEvent::class);
        $isNotificationEvent->isSatisfiedBy($event)->willReturn(true);

        $guard = $this->prophesize(GuardInterface::class);
        $guard->isGranted($event)->willReturn(false);
        $guard->onFailure($event)->shouldBeCalled();

        $application  = $this->prophesize(ApplicationInterface::class);
        $eventManager = $this->prophesize(EventManagerInterface::class);
        $application->getEventManager()->willReturn($eventManager->reveal());
        $event->setApplication($application->reveal());

        $listener = new GuardListener($isNotificationEvent->reveal(), [$guard->reveal()]);

        $listener->onResult($event);

        $this->assertNotEmpty($event->getError());
        $this->assertNotNull($event->getParam('exception'));
    }
}
