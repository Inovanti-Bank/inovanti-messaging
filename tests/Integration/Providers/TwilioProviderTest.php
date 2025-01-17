<?php

namespace Tests\Integration\Providers;

use Illuminate\Http\Client\Response;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\TwilioProvider;
use Mockery;
use PHPUnit\Framework\TestCase;

class TwilioProviderTest extends TestCase
{
    protected TwilioProvider $twilioProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->twilioProvider = new TwilioProvider(
            getenv('TWILIO_ACCOUNT_SID'),
            getenv('TWILIO_AUTH_TOKEN')
        );
    }

    public function test_it_sends_sms_successfully()
    {
        $mockResponse = Mockery::mock(Response::class, function ($mock) {
            $mock->shouldReceive('json')->andReturn([
                'status' => 'success',
                'message_id' => 'SMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            ]);
            $mock->shouldReceive('successful')->andReturn(true);
        });

        $httpClientMock = Mockery::mock('alias:Illuminate\Support\Facades\Http');
        $httpClientMock->shouldReceive('post')
            ->once()
            ->andReturn($mockResponse);

        $messageData = new MessageData(
            type: 'sms',
            to: getenv('TWILIO_TEST_SMS_PHONE'),
            from: getenv('TWILIO_SMS_FROM'),
            content: 'Teste de SMS via Twilio'
        );

        $response = $this->twilioProvider->sendMessage($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('message_id', $response);
    }
}
