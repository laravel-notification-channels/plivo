<?php

namespace NotificationChannels\Plivo\Test;

use NotificationChannels\Plivo\PlivoChannel;
use NotificationChannels\Plivo\PlivoMessage;
use Mockery;

class PlivoChannelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base configuration for the plivo service.
     * @var array
     */
    protected $baseConfig = [
        'auth_id' => 'UNIT_TEST_AUTH_ID',
        'auth_token' => 'UNIT_TEST_AUTH_TOKEN',
        'from_number' => '18885551111'
    ];

    /**
     * The mocked notifiable instance.
     *
     * @var mixed
     */
    protected $notifiable;

    public function setup()
    {
        parent::setup();

        $this->notifiable = Mockery::mock('notifiable')
            ->shouldReceive('routeNotificationFor')
            ->with('plivo')
            ->andReturn('18885552222')
            ->getMock();
    }

    /** @test */
    public function it_has_empty_webhook_if_missing_from_config()
    {
        $config = $this->config($withWebhook = false);

        $plivo = $plivo = $plivo = $this->mockedPlivo($config, [
            'src' => $config['from_number'],
            'dst' => $this->notifiable->routeNotificationFor('plivo'),
            'text' => 'Content',
            'url' => ''
        ]);

        $notification = $this->notification(new PlivoMessage('Content'));

        (new PlivoChannel($plivo))
            ->send($this->notifiable, $notification);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_uses_default_webhook_in_config()
    {
        $config = $this->config($withwebhook = true);

        $plivo = $plivo = $this->mockedPlivo($config, [
            'src' => $config['from_number'],
            'dst' => $this->notifiable->routeNotificationFor('plivo'),
            'text' => 'Content',
            'url' => 'https://defaultexample.com'
        ]);

        $notification = $this->notification(new PlivoMessage('Content'));

        (new PlivoChannel($plivo))
            ->send($this->notifiable, $notification);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_prefers_message_webhook_over_default_webhook()
    {
        $config = $this->config($withwebhook = true);

        $plivo = $this->mockedPlivo($config, [
            'src' => $config['from_number'],
            'dst' => $this->notifiable->routeNotificationFor('plivo'),
            'text' => 'Content',
            'url' => 'https://messagewebhook.com'
        ]);

        $notification = $this->notification(new PlivoMessage('Content', 'https://messagewebhook.com'));

        (new PlivoChannel($plivo))
            ->send($this->notifiable, $notification);

        $this->assertTrue(true);
    }

    private function notification($withPlivoMessage)
    {
        return Mockery::mock('\Illuminate\Notifications\Notification')
            ->shouldReceive('toPlivo')
            ->andReturn($withPlivoMessage)
            ->getMock();
    }

    private function config($withWebhook)
    {
        if ($withWebhook) {
            return array_merge($this->baseConfig, ['webhook' => 'https://defaultexample.com']);
        }

        return $this->baseConfig;
    }

    private function mockedPlivo($config, array $arguments)
    {
        return Mockery::mock('\NotificationChannels\Plivo\Plivo', [$config])
            ->shouldReceive('send_message')
            ->once()
            ->with($arguments)
            ->andReturn(['status' => 202])
            ->getMock()
            ->makePartial();
    }
}
