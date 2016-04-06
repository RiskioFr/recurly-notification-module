<?php
namespace Riskio\Recurly\NotificationModule\Controller;

use Riskio\Recurly\NotificationModule\Notification\Handler as NotificationHandler;
use Zend\Mvc\Controller\AbstractActionController;

class NotificationController extends AbstractActionController
{
    /**
     * @var NotificationHandler
     */
    private $notificationHandler;

    public function __construct(NotificationHandler $handler)
    {
        $this->notificationHandler = $handler;
    }

    public function pushAction()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        $xml = $request->getContent();
        if (empty($xml)) {
            $response->setStatusCode(202);
            return $response;
        }

        $this->notificationHandler->handle($xml);

        $response->setStatusCode(200);

        return $response;
    }
}
