<?php
namespace Riskio\Recurly\NotificationModuleTest\Guard;

use Prophecy\Argument;
use Riskio\Recurly\NotificationModule\Guard\IpGuard;
use VectorFace\Whip\Whip;
use Zend\Http\Response as HttpResponse;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class IpGuardTest extends \PHPUnit_Framework_TestCase
{
    public function testIsGranted_GivenValidIpAddress_ShouldReturnTrue()
    {
        $whip = $this->prophesize(Whip::class);
        $whip->getValidIpAddress()->willReturn('127.0.0.1');

        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new IpGuard($whip->reveal(), $logger->reveal());

        $result = $listener->isGranted(new MvcEvent());

        $this->assertTrue($result);
    }

    public function testIsGranted_GivenInvalidIpAddress_ShouldReturnFalse()
    {
        $whip = $this->prophesize(Whip::class);
        $whip->getValidIpAddress()->willReturn(false);

        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new IpGuard($whip->reveal(), $logger->reveal());

        $result = $listener->isGranted(new MvcEvent());

        $this->assertFalse($result);
    }

    public function testOnFailure_ShouldUpdateHttpResponseStatusCode()
    {
        $response = $this->prophesize(HttpResponse::class);
        $response->setStatusCode(HttpResponse::STATUS_CODE_403)->shouldBeCalled();
        $event = new MvcEvent();
        $event->setResponse($response->reveal());
        $whip = $this->prophesize(Whip::class);
        $whip->getIpAddress()->willReturn('127.0.0.1');
        $logger = $this->prophesize(LoggerInterface::class);

        $listener = new IpGuard($whip->reveal(), $logger->reveal());

        $listener->onFailure($event);
    }
}
