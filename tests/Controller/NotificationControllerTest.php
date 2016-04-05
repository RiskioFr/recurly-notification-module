<?php
namespace Riskio\Recurly\NotificationModuleTest\Controller;

use Riskio\Recurly\NotificationModule\Controller\NotificationController;
use Riskio\Recurly\NotificationModule\Notification\Handler as NotificationHandler;
use Zend\Http\Response as HttpResponse;

class NotificationControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexActionWithRequestContent()
    {
        $requestContent = 'foo';

        $handler = $this->prophesize(NotificationHandler::class);
        $handler->handle($requestContent)->shouldBeCalled();

        $controller = new NotificationController($handler->reveal());

        $request = $controller->getRequest();
        $request->setContent($requestContent);

        $response = $controller->pushAction();

        $this->assertEquals(HttpResponse::STATUS_CODE_200, $response->getStatusCode());
    }

    public function testIndexActionWithEmptyRequestContent()
    {
        $handler = $this->prophesize(NotificationHandler::class);
        $handler->handle()->shouldNotBeCalled();

        $controller = new NotificationController($handler->reveal());

        $response = $controller->pushAction();

        $this->assertEquals(HttpResponse::STATUS_CODE_202, $response->getStatusCode());
    }
}