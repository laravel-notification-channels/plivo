<?php

namespace NotificationChannels\Plivo;

use NotificationChannels\Plivo\Exceptions\CouldNotSendNotification;
use NotificationChannels\Plivo\Events\MessageWasSent;
use NotificationChannels\Plivo\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class PlivoChannel
{
    /**
     * The Plivo instance.
     *
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
     * Create a new Plivo channel instance.
     *
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

        $shouldSendMessage = event(new SendingMessage($notifiable, $notification), [], true) !== false;

        if (! $shouldSendMessage) {
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

        event(new MessageWasSent($notifiable, $notification));
    }
}
