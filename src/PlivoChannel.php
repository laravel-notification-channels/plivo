<?php

namespace NotificationChannels\Plivo;

use Illuminate\Notifications\Notification;
use NotificationChannels\Plivo\Exceptions\CouldNotSendNotification;

class PlivoChannel
{
    /**
     * @var \NotificationChannels\Plivo\Plivo;
     */
    protected $plivo;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * @return  void
     */
    public function __construct(Plivo $plivo)
    {
        $this->plivo = $plivo;
        $this->from = $this->plivo->from();
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Plivo\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('plivo')) {
            return;
        }

        $message = $notification->toPlivo($notifiable);

        if (is_string($message)) {
            $message = new PlivoMessage($message);
        }

        $response = $this->plivo->send_message([
            'src' => $message->from ?: $this->from,
            'dst' => $to,
            'text' => trim($message->content),
        ]);

        if ($response['status'] !== 202) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }
}
