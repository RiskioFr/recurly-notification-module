<?php
namespace Riskio\Recurly\NotificationModule\Exception;

class UnauthorizedException
    extends \RuntimeException
    implements UnauthorizedExceptionInterface
{
    public static function create() : self
    {
        return new self('You are not authorized to access this resource');
    }
}
