<?php
namespace Riskio\Recurly\NotificationModuleTest\Specification;

use Riskio\Recurly\NotificationModule\Module;
use Riskio\Recurly\NotificationModule\Specification\IsNotificationEvent;
use Prophecy\Argument;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\RequestInterface;

class IsNotificationEventTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSatisfiedBy_GivenHttpRequestAndValidMatchedRouteName_ShouldReturnTrue()
    {
        $request = new HttpRequest();
        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName(Module::RECURLY_NOTIFICATION_ROUTE);
        $event = new MvcEvent();
        $event->setRequest($request);
        $event->setRouteMatch($routeMatch);

        $specification = new IsNotificationEvent();

        $result = $specification->isSatisfiedBy($event);

        $this->assertTrue($result);
    }

    public function testIsSatisfiedBy_GivenNoHttpRequest_ShouldReturnFalse()
    {
        $request = $this->prophesize(RequestInterface::class);
        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName(Module::RECURLY_NOTIFICATION_ROUTE);
        $event = new MvcEvent();
        $event->setRequest($request->reveal());
        $event->setRouteMatch($routeMatch);

        $specification = new IsNotificationEvent();

        $result = $specification->isSatisfiedBy($event);

        $this->assertFalse($result);
    }

    public function testIsSatisfiedBy_GivenInvalidMatchedRouteName_ShouldReturnFalse()
    {
        $request = new HttpRequest();
        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName('invalid');
        $event = new MvcEvent();
        $event->setRequest($request);
        $event->setRouteMatch($routeMatch);

        $specification = new IsNotificationEvent();

        $result = $specification->isSatisfiedBy($event);

        $this->assertFalse($result);
    }
}
