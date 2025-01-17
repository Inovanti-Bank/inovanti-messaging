<?php

namespace Tests\Unit\Providers;

use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\SendGridProvider;
use Mockery;
use PHPUnit\Framework\TestCase;
use SendGrid;

class SendGridProviderTest extends TestCase
{
    public function test_it_sends_email_successfully()
    {
        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('statusCode')->andReturn(202);
        $responseMock->shouldReceive('headers')->andReturn(['X-Message-Id' => ['email-123']]);

        $sendGridMock = Mockery::mock(SendGrid::class);
        $sendGridMock->shouldReceive('send')
            ->once()
            ->andReturn($responseMock);

        $provider = new SendGridProvider('fake_api_key');
        $provider->setMockInstance($sendGridMock);

        $messageData = new MessageData(
            type: 'email',
            to: 'user@example.com',
            from: 'no-reply@example.com',
            content: 'Test email',
            metadata: ['subject' => 'Test Subject']
        );

        $response = $provider->sendMessage($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('email-123', $response['message_id']);
    }
}
