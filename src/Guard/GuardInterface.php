<?php
namespace Riskio\Recurly\NotificationModule\Guard;

use Zend\Mvc\MvcEvent;

interface GuardInterface
{
    public function onSuccess(MvcEvent $event);

    public function onFailure(MvcEvent $event);

    public function isGranted(MvcEvent $event) : bool;
}
