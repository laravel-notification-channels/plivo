<?php

namespace NotificationChannels\Plivo;

class PlivoMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * The webhook url plivo will call for status updates.
     *
     * @var string
     */
    public $webhook;

    /**
     * Create a new message instance.
     *
     * @param  string $content
     *
     * @return static
     */
    public static function create($content = '', $webhook = '')
    {
        return new static($content, $webhook);
    }

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     */
    public function __construct($content = '', $webhook = '')
    {
        $this->content = $content;

        $this->webhook = $webhook;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the webhook url plivo will call for status updates.
     *
     * @param  string  $webhook
     *
     * @return $this
     */
    public function webhook($webhook)
    {
        $this->webhook = $webhook;

        return $this;
    }
}
