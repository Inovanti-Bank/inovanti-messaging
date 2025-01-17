<?php

namespace Tests\Unit\Providers;

use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\TwilioProvider;
use Mockery;
use PHPUnit\Framework\TestCase;
use Twilio\Rest\Client;

class TwilioProviderTest extends TestCase
{
    public function test_it_sends_sms_successfully()
    {
        $twilioMock = Mockery::mock(Client::class);
        $twilioMock->messages = Mockery::mock();
        $twilioMock->messages->shouldReceive('create')
            ->once()
            ->andReturn((object) ['sid' => 'message-123']);

        $provider = new TwilioProvider('fake_sid', 'fake_token');
        $provider->setMockInstance($twilioMock);

        $messageData = new MessageData(
            type: 'sms',
            to: '+5511987654321',
            from: '+15551234567',
            content: 'Test message'
        );

        $response = $provider->sendMessage($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('message-123', $response['message_id']);
    }
}
