<?php

namespace Tests\Integration\Providers;

use Illuminate\Http\Client\Response;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\SendGridProvider;
use Mockery;
use PHPUnit\Framework\TestCase;

class SendGridProviderTest extends TestCase
{
    protected SendGridProvider $sendGridProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sendGridProvider = new SendGridProvider(getenv('SENDGRID_API_KEY'));
    }

    public function test_it_sends_email_successfully()
    {
        $mockResponse = Mockery::mock(Response::class, function ($mock) {
            $mock->shouldReceive('json')->andReturn([
                'status' => 'success',
                'message_id' => '123456789',
            ]);
            $mock->shouldReceive('successful')->andReturn(true);
        });

        $httpClientMock = Mockery::mock('alias:Illuminate\Support\Facades\Http');
        $httpClientMock->shouldReceive('post')
            ->once()
            ->andReturn($mockResponse);

        $messageData = new MessageData(
            type: 'email',
            to: getenv('SENDGRID_TEST_EMAIL'),
            from: getenv('SENDGRID_FROM_EMAIL'),
            content: 'Teste de e-mail via SendGrid',
            metadata: ['subject' => 'Testando envio via SendGrid']
        );

        $response = $this->sendGridProvider->sendMessage($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('message_id', $response);
    }
}
