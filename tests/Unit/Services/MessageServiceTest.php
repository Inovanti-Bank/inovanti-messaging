<?php

namespace Tests\Unit\Services;

use Illuminate\Events\Dispatcher;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Events\MessageFailed;
use InovantiBank\Messaging\Events\MessageSent;
use InovantiBank\Messaging\Exceptions\MessagingException;
use InovantiBank\Messaging\Services\MessageService;
use InovantiBank\Messaging\Services\SendGridEmailService;
use InovantiBank\Messaging\Services\TwilioSmsService;
use InovantiBank\Messaging\Services\TwilioWhatsAppService;
use Mockery;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    protected $eventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = Mockery::mock(Dispatcher::class);
        $this->eventDispatcher->shouldIgnoreMissing();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_it_sends_sms_via_twilio()
    {
        $twilioSmsMock = Mockery::mock(TwilioSmsService::class);
        $twilioSmsMock->shouldReceive('send')
            ->once()
            ->andReturn(['status' => 'success', 'message_id' => 'sms-123']);

        $messageService = new MessageService([
            'sms' => $twilioSmsMock,
        ], $this->eventDispatcher);

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(MessageSent::class));

        $messageData = new MessageData('sms', '+5511987654321', '+15551234567', 'Test SMS');

        $response = $messageService->send($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('sms-123', $response['message_id']);
    }

    public function test_it_sends_whatsapp_via_twilio()
    {
        $twilioWhatsAppMock = Mockery::mock(TwilioWhatsAppService::class);
        $twilioWhatsAppMock->shouldReceive('send')
            ->once()
            ->andReturn(['status' => 'success', 'message_id' => 'whatsapp-123']);

        $messageService = new MessageService([
            'whatsapp' => $twilioWhatsAppMock,
        ], $this->eventDispatcher);

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(MessageSent::class));

        $messageData = new MessageData('whatsapp', '+5511987654321', '+15551234567', 'Test WhatsApp');

        $response = $messageService->send($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('whatsapp-123', $response['message_id']);
    }

    public function test_it_sends_email_via_sendgrid()
    {
        // Mock do SendGridEmailService
        $twilioEmailMock = Mockery::mock(SendGridEmailService::class);
        $twilioEmailMock->shouldReceive('send')
            ->once()
            ->andReturn(['status' => 'success', 'message_id' => 'email-123']);

        $messageService = new MessageService([
            'email' => $twilioEmailMock,
        ], $this->eventDispatcher);

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(MessageSent::class));

        $messageData = new MessageData('email', 'user@example.com', 'no-reply@example.com', 'Test Email');

        $response = $messageService->send($messageData);

        $this->assertEquals('success', $response['status']);
        $this->assertEquals('email-123', $response['message_id']);
    }

    public function test_it_fails_to_send_when_no_provider_configured()
    {
        $messageService = new MessageService([], $this->eventDispatcher);

        $messageData = new MessageData('sms', '+5511987654321', '+15551234567', 'Test SMS');

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(MessageFailed::class));

        $this->expectException(MessagingException::class);
        $this->expectExceptionMessage('Nenhum provedor configurado para o tipo: sms');

        try {
            $messageService->send($messageData);
        } catch (MessagingException $e) {
            $this->eventDispatcher->shouldHaveReceived('dispatch')
                ->once()
                ->with(Mockery::type(MessageFailed::class));

            throw $e;
        }
    }
}
