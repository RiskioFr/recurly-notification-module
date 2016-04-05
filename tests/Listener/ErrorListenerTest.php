<?php
namespace Riskio\Recurly\NotificationModuleTest\Listener;

use Prophecy\Argument;
use Riskio\Recurly\NotificationModule\Exception;
use Riskio\Recurly\NotificationModule\Listener\ErrorListener;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

class ErrorListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testAttachEvent()
    {
        $listener = new ErrorListener();

        $eventManager = $this->prophesize(EventManagerInterface::class);
        $eventManager
            ->attach(MvcEvent::EVENT_DISPATCH_ERROR, Argument::type('array'))
            ->shouldBeCalled();

        $listener->attach($eventManager->reveal());
    }

    public function testFillEvent()
    {
        $event = new MvcEvent();
        $event->setParam('exception', Exception\UnauthorizedException::create());

        $response = new HttpResponse();
        $event->setResponse($response);

        $listener = new ErrorListener();
        $listener->onError($event);
    }

    public function testFillEventWithoutException()
    {
        $event = new MvcEvent();

        $response = new HttpResponse();
        $event->setResponse($response);

        $listener = new ErrorListener();
        $listener->onError($event);

        $this->assertEquals(HttpResponse::STATUS_CODE_200, $response->getStatusCode());
    }
}