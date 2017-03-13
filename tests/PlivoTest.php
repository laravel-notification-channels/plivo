<?php

namespace NotificationChannels\Plivo\Test;

use NotificationChannels\Plivo\Plivo;

class PlivoTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_instantiate_from_config_without_webhook()
    {
        $config = [
            'auth_id' => 'UNIT_TEST_AUTH_ID',
            'auth_token' => 'UNIT_TEST_AUTH_ID',
            'from_number' => '18885551111',
        ];

        $plivo = new Plivo($config);

        $this->assertEquals('18885551111', $plivo->from());

        $this->assertSame('', $plivo->webhook());
    }

    /** @test */
    public function it_can_instantiate_from_config_with_webhook()
    {
        $config = [
            'auth_id' => 'UNIT_TEST_AUTH_ID',
            'auth_token' => 'UNIT_TEST_AUTH_ID',
            'from_number' => '18885551111',
            'webhook' => 'https://examplewebhook.com',
        ];

        $plivo = new Plivo($config);

        $this->assertEquals('18885551111', $plivo->from());

        $this->assertEquals('https://examplewebhook.com', $plivo->webhook());
    }
}
