<?php
namespace Riskio\Recurly\NotificationModuleTest\Listener;

use Prophecy\Argument;
use Riskio\Recurly\NotificationModule\Listener\IpListener;
use Riskio\Recurly\NotificationModule\Module;
use VectorFace\Whip\Whip;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class IpListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testAttachEvent()
    {
        $whip   = $this->prophesize(Whip::class);
        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new IpListener($whip->reveal(), $logger->reveal());

        $eventManager = $this->prophesize(EventManagerInterface::class);
        $eventManager
            ->attach(MvcEvent::EVENT_ROUTE, Argument::type('array'), Argument::type('int'))
            ->shouldBeCalled();

        $listener->attach($eventManager->reveal());
    }

    public function testProperlyFillEventOnAuthorization()
    {
        $whip = $this->prophesize(Whip::class);
        $whip->getValidIpAddress()->willReturn(true);

        $logger = $this->prophesize(LoggerInterface::class);

        $event      = new MvcEvent();
        $request    = new HttpRequest();
        $response   = new HttpResponse();

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName(Module::RECURLY_NOTIFICATION_ROUTE);

        $event
            ->setRequest($request)
            ->setResponse($response)
            ->setRouteMatch($routeMatch);

        $listener = new IpListener($whip->reveal(), $logger->reveal());
        $listener->onResult($event);

        $this->assertEmpty($event->getError());
        $this->assertNull($event->getParam('exception'));
    }

    public function testProperlySetUnauthorizedAndTriggerEventOnUnauthorization()
    {
        $whip = $this->prophesize(Whip::class);
        $whip->getValidIpAddress()->willReturn(false);
        $whip->getIpAddress()->willReturn('127.0.0.1');

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->info(Argument::any())->shouldBeCalled();

        $event      = new MvcEvent();
        $request    = new HttpRequest();
        $response   = new HttpResponse();

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName(Module::RECURLY_NOTIFICATION_ROUTE);

        $application  = $this->prophesize(Application::class);
        $eventManager = $this->prophesize(EventManagerInterface::class);

        $application->getEventManager()->willReturn($eventManager->reveal());

        $event
            ->setRequest($request)
            ->setResponse($response)
            ->setRouteMatch($routeMatch)
            ->setApplication($application->reveal());

        $listener = new IpListener($whip->reveal(), $logger->reveal());

        $listener->onResult($event);

        $this->assertNotEmpty($event->getError());
        $this->assertNotNull($event->getParam('exception'));

        $this->assertEquals(HttpResponse::STATUS_CODE_403, $response->getStatusCode());
    }
}
