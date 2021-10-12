<?php

namespace NotificationChannels\Plivo;

use Illuminate\Notifications\Notification;
use NotificationChannels\Plivo\Exceptions\CouldNotSendNotification;
use Plivo\Resources\Message\MessageCreateResponse;

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
     * @return MessageCreateResponse|null
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('plivo')) {
            return null;
        }

        $message = $notification->toPlivo($notifiable);

        if (is_string($message)) {
            $message = new PlivoMessage($message);
        }

        /** @var MessageCreateResponse $response */
        $response = $this->plivo->messages->create(
            $message->from ?: $this->from,
            [$to],
            trim($message->content)
        );

        if ($response->statusCode !== 202) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }
}
