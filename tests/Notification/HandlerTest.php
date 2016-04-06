<?php
namespace Riskio\Recurly\NotificationModuleTest\Notification;

use Riskio\Recurly\NotificationModule\Notification\Handler as NotificationHandler;
use Zend\EventManager\EventManagerInterface;
use Prophecy\Argument;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testNotificationHandler()
    {
        $xml = '<new_account_notification>
            <account>
                <account_code>1</account_code>
                <username nil="true"></username>
                <email>verena@example.com</email>
                <first_name>Verena</first_name>
                <last_name>Example</last_name>
                <company_name nil="true"></company_name>
            </account>
        </new_account_notification>';

        $handler = new NotificationHandler();

        $eventManager = $this->prophesize(EventManagerInterface::class);
        $eventManager
            ->setIdentifiers(Argument::any())
            ->shouldBeCalled();
        $eventManager
            ->trigger('new_account_notification', null, Argument::type('array'))
            ->shouldBeCalled();
        $handler->setEventManager($eventManager->reveal());

        $handler->handle($xml);
    }
}
