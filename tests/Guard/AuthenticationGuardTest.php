<?php
namespace Riskio\Recurly\NotificationModuleTest\Guard;

use Psr\Log\LoggerInterface;
use Riskio\Recurly\NotificationModule\Guard\AuthenticationGuard;
use Prophecy\Argument;
use Zend\Authentication\Adapter\Http as AuthAdapter;
use Zend\Authentication\Result as AuthResult;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

class AuthenticationGuardTest extends \PHPUnit_Framework_TestCase
{
    public function testIsGranted_GivenValidAuthentication_ShouldReturnTrue()
    {
        $authenticationResult = $this->prophesize(AuthResult::class);
        $authenticationResult->isValid()->willReturn(true);

        $authAdapter = $this->prophesize(AuthAdapter::class);
        $authAdapter
            ->authenticate()
            ->willReturn($authenticationResult->reveal());

        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new AuthenticationGuard($authAdapter->reveal(), $logger->reveal());

        $result = $listener->isGranted(new MvcEvent());

        $this->assertTrue($result);
    }

    public function testIsGranted_GivenInvalidAuthentication_ShouldReturnFalse()
    {
        $authenticationResult = $this->prophesize(AuthResult::class);
        $authenticationResult->isValid()->willReturn(false);

        $authAdapter = $this->prophesize(AuthAdapter::class);
        $authAdapter
            ->authenticate()
            ->willReturn($authenticationResult->reveal());

        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new AuthenticationGuard($authAdapter->reveal(), $logger->reveal());

        $result = $listener->isGranted(new MvcEvent());

        $this->assertFalse($result);
    }

    public function testOnFailure_ShouldLogAnErrorMessage()
    {
        $event = new MvcEvent();
        $event->setResponse(new HttpResponse());
        $authAdapter = $this->prophesize(AuthAdapter::class);
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->info(Argument::any())->shouldBeCalled();

        $listener = new AuthenticationGuard($authAdapter->reveal(), $logger->reveal());

        $listener->onFailure($event);
    }

    public function testOnFailure_ShouldUpdateHttpResponseStatusCode()
    {
        $response = $this->prophesize(HttpResponse::class);
        $response->setStatusCode(HttpResponse::STATUS_CODE_401)->shouldBeCalled();
        $event = new MvcEvent();
        $event->setResponse($response->reveal());
        $authAdapter = $this->prophesize(AuthAdapter::class);
        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new AuthenticationGuard($authAdapter->reveal(), $logger->reveal());

        $listener->onFailure($event);
    }
}
