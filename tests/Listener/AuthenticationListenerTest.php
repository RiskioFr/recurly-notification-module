<?php
namespace Riskio\Recurly\NotificationModuleTest\Listener;

use Riskio\Recurly\NotificationModule\Listener\AuthenticationListener;
use Riskio\Recurly\NotificationModule\Module;
use Prophecy\Argument;
use Zend\Mvc\ApplicationInterface;
use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Authentication\Result as AuthResult;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class AuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testAttachEvent()
    {
        $authAdapter = $this->prophesize(AuthAdapter::class);
        $logger      = $this->prophesize(LoggerInterface::class);

        $listener = new AuthenticationListener($authAdapter->reveal(), $logger->reveal());

        $eventManager = $this->prophesize(EventManagerInterface::class);
        $eventManager
            ->attach(MvcEvent::EVENT_ROUTE, Argument::type('array'), Argument::type('int'))
            ->shouldBeCalled();

        $listener->attach($eventManager->reveal());
    }

    public function testProperlyFillEventOnAuthorization()
    {
        $event      = new MvcEvent();
        $request    = new HttpRequest();
        $response   = new HttpResponse();
        $routeMatch = new RouteMatch([]);

        $routeMatch->setMatchedRouteName(Module::RECURLY_NOTIFICATION_ROUTE);
        $event
            ->setRequest($request)
            ->setResponse($response)
            ->setRouteMatch($routeMatch);

        $authenticationResult = $this->prophesize(AuthResult::class);
        $authenticationResult->isValid()->willReturn(true)->shouldBeCalled();

        $authAdapter = $this->prophesize(AuthAdapter::class);
        $authAdapter
            ->authenticate()
            ->willReturn($authenticationResult->reveal());

        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new AuthenticationListener($authAdapter->reveal(), $logger->reveal());
        $listener->onResult($event);

        $this->assertEmpty($event->getError());
        $this->assertNull($event->getParam('exception'));
    }

    public function testProperlySetUnauthorizedAndTriggerEventOnUnauthorization()
    {
        $event      = new MvcEvent();
        $response   = new HttpResponse();

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName(Module::RECURLY_NOTIFICATION_ROUTE);

        $application  = $this->prophesize(ApplicationInterface::class);
        $eventManager = $this->prophesize(EventManagerInterface::class);

        $application->getEventManager()->willReturn($eventManager->reveal());

        $event
            ->setRequest(new HttpRequest())
            ->setResponse($response)
            ->setRouteMatch($routeMatch)
            ->setApplication($application->reveal());

        $authenticationResult = $this->prophesize(AuthResult::class);
        $authenticationResult->isValid()->willReturn(false);

        $authAdapter = $this->prophesize(AuthAdapter::class);
        $authAdapter
            ->authenticate()
            ->willReturn($authenticationResult->reveal());

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->info(Argument::any())->shouldBeCalled();

        $listener = new AuthenticationListener($authAdapter->reveal(), $logger->reveal());

        $listener->onResult($event);

        $this->assertNotEmpty($event->getError());
        $this->assertNotNull($event->getParam('exception'));

        $this->assertEquals(HttpResponse::STATUS_CODE_401, $response->getStatusCode());
    }
}
