<?php

namespace Tests\Unit\Providers;

use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\SendGridProvider;
use Mockery;
use PHPUnit\Framework\TestCase;
use SendGrid;
use SendGrid\Mail\Mail;
use SendGrid\Response;

class SendGridProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_sends_email_successfully_and_preserves_existing_contract(): void
    {
        $response = new Response(202, '', [
            'HTTP/1.1 202 Accepted',
            'X-Message-Id: email-123',
        ]);

        $sendGridMock = Mockery::mock(SendGrid::class);
        $sendGridMock->shouldReceive('send')
            ->once()
            ->with(Mockery::type(Mail::class))
            ->andReturn($response);

        $provider = new SendGridProvider('fake_api_key');
        $provider->setMockInstance($sendGridMock);

        $messageData = new MessageData(
            type: 'email',
            to: 'user@example.com',
            from: 'no-reply@example.com',
            content: 'Test email',
            metadata: ['subject' => 'Test Subject']
        );

        $result = $provider->sendMessage($messageData);

        $this->assertSame('success', $result['status']);
        $this->assertSame('email-123', $result['message_id']);
        $this->assertSame('email-123', $result['x_message_id']);
        $this->assertSame('Test Subject', $result['subject']);
        $this->assertSame(202, $result['http_status']);
    }

    public function test_it_sends_and_returns_sendgrid_tracking_context(): void
    {
        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('statusCode')->andReturn(202);
        $responseMock->shouldReceive('headers')->andReturn([
            'x-message-id' => ['email-456'],
            'x-ratelimit-remaining' => '599',
        ]);
        $responseMock->shouldReceive('body')->andReturn('{"accepted":true}');

        $sendGridMock = Mockery::mock(SendGrid::class);
        $sendGridMock->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function (Mail $email): bool {
                $payload = json_decode(json_encode($email), true);

                $this->assertSame(
                    ['name' => 'Inovanti', 'email' => 'no-reply@example.com'],
                    $payload['from']
                );
                $this->assertSame(
                    ['name' => 'Support', 'email' => 'support@example.com'],
                    $payload['reply_to']
                );
                $this->assertSame('Structured subject', $payload['subject']);
                $this->assertSame(['transactional', 'credit'], $payload['categories']);
                $this->assertSame([
                    'operation' => 'proposal_created',
                    'correlation_id' => 'correlation-123',
                ], $payload['personalizations'][0]['custom_args']);
                $this->assertSame(
                    ['X-Trace-Id' => 'trace-123'],
                    $payload['personalizations'][0]['headers']
                );

                return true;
            }))
            ->andReturn($responseMock);

        $provider = new SendGridProvider('fake_api_key');
        $provider->setMockInstance($sendGridMock);

        $messageData = new MessageData(
            type: 'email',
            to: 'user@example.com',
            from: 'no-reply@example.com',
            content: 'Test email',
            metadata: ['audit_source' => 'credit-api'],
            subject: 'Structured subject',
            fromName: 'Inovanti',
            replyTo: 'support@example.com',
            replyToName: 'Support',
            categories: ['transactional', 'credit'],
            customArgs: ['operation' => 'proposal_created'],
            headers: ['X-Trace-Id' => 'trace-123'],
            correlationId: 'correlation-123',
        );

        $result = $provider->sendMessage($messageData);

        $this->assertSame('email-456', $result['message_id']);
        $this->assertSame('correlation-123', $result['correlation_id']);
        $this->assertSame([
            'operation' => 'proposal_created',
            'correlation_id' => 'correlation-123',
        ], $result['custom_args']);
        $this->assertSame(['accepted' => true], $result['response_body']);
        $this->assertSame('599', $result['response_headers']['x-ratelimit-remaining']);
    }

    public function test_it_enriches_message_details_without_removing_sendgrid_fields(): void
    {
        $response = new Response(200, json_encode([
            'msg_id' => 'email-789',
            'status' => 'delivered',
            'categories' => ['transactional'],
            'unique_args' => '{"correlation_id":"correlation-789"}',
            'events' => [['event_name' => 'delivered']],
        ]));

        $messageResource = Mockery::mock();
        $messageResource->shouldReceive('get')->once()->andReturn($response);

        $messagesResource = Mockery::mock();
        $messagesResource->shouldReceive('_')->once()->with('email-789')->andReturn($messageResource);

        $sendGridMock = Mockery::mock(SendGrid::class);
        $sendGridMock->client = Mockery::mock();
        $sendGridMock->client->shouldReceive('messages')->once()->andReturn($messagesResource);

        $provider = new SendGridProvider('fake_api_key');
        $provider->setMockInstance($sendGridMock);

        $result = $provider->getMessageById('email-789');

        $this->assertSame('email-789', $result['msg_id']);
        $this->assertSame('email-789', $result['message_id']);
        $this->assertSame('delivered', $result['status']);
        $this->assertSame(['transactional'], $result['categories']);
        $this->assertSame('{"correlation_id":"correlation-789"}', $result['unique_args']);
        $this->assertSame(['correlation_id' => 'correlation-789'], $result['custom_args']);
        $this->assertSame(200, $result['http_status']);
    }
}
