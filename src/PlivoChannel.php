<?php

namespace NotificationChannels\Plivo;

use NotificationChannels\Plivo\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

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
     * The webhook url that plivo will send status change notifications to.
     *
     * @var string
     */
    protected $webhook;

    /**
     * @return  void
     */
    public function __construct(Plivo $plivo)
    {
        $this->plivo = $plivo;
        $this->from = $this->plivo->from();
        $this->webhook = $this->plivo->webhook();
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
            'url' => $message->webhook ?: $this->webhook
        ]);

        if ($response['status'] !== 202) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }
}
