<?php

namespace Tests\Unit\Services;

use InovantiBank\Messaging\Providers\SendGridProvider;
use InovantiBank\Messaging\Services\SendGridEmailService;
use Mockery;
use PHPUnit\Framework\TestCase;

class SendGridEmailServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_gets_message_details_through_the_service(): void
    {
        $provider = Mockery::mock(SendGridProvider::class);
        $provider->shouldReceive('getMessageById')
            ->once()
            ->with('email-123')
            ->andReturn([
                'message_id' => 'email-123',
                'status' => 'delivered',
            ]);

        $service = new SendGridEmailService($provider);

        $result = $service->getMessageById('email-123');

        $this->assertSame('email-123', $result['message_id']);
        $this->assertSame('delivered', $result['status']);
    }
}
