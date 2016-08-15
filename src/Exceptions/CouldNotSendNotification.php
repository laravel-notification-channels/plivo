<?php

namespace NotificationChannels\Plivo\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Notification was not sent. Plivo responded with `{$response['status']}: {$response['response']['error']}`");
    }
}
