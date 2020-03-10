<?php

namespace NotificationChannels\Plivo\Test;

use NotificationChannels\Plivo\PlivoMessage;
use PHPUnit\Framework\TestCase;

class PlivoMessageTest extends TestCase
{
    /** @var \NotificationChannels\Plivo\PlivoMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();

        $this->message = new PlivoMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new PlivoMessage('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('myMessage');

        $this->assertEquals('myMessage', $this->message->content);
    }

    /** @test */
    public function it_can_set_the_from_number()
    {
        $this->message->from('1234567890');

        $this->assertEquals('1234567890', $this->message->from);
    }
}
