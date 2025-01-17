<?php

namespace Tests\Unit\Services;

use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\SendGridProvider;
use InovantiBank\Messaging\Providers\TwilioProvider;
use InovantiBank\Messaging\Services\MessageService;
use Mockery;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    public function test_it_sends_sms_via_twilio()
    {
        $twilioMock = Mockery::mock(TwilioProvider::class);
        $twilioMock->shouldReceive('sendMessage')
            ->once()
            ->andReturn(['status' => 'success', 'message_id' => 'sms-123']);

        $config = [
            'twilio' => ['account_sid' => 'fake_sid', 'auth_token' => 'fake_token'],
        ];

        $messageService = new MessageService($config);
        $messageService->setMockProvider('sms', $twilioMock);

        $messageData = new MessageData('sms', '+5511987654321', '+15551234567', 'Test SMS');

        $response = $messageService->send($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('sms-123', $response['message_id']);
    }

    public function test_it_sends_email_via_sendgrid()
    {
        $sendGridMock = Mockery::mock(SendGridProvider::class);
        $sendGridMock->shouldReceive('sendMessage')
            ->once()
            ->andReturn(['status' => 'success', 'message_id' => 'email-123']);

        $config = [
            'sendgrid' => ['api_key' => 'fake_api_key'],
        ];

        $messageService = new MessageService($config);
        $messageService->setMockProvider('email', $sendGridMock);

        $messageData = new MessageData('email', 'user@example.com', 'no-reply@example.com', 'Test Email');

        $response = $messageService->send($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('email-123', $response['message_id']);
    }
}
