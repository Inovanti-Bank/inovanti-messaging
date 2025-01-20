<?php

namespace InovantiBank\Messaging\Providers;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use InovantiBank\Messaging\Services\MessageService;
use InovantiBank\Messaging\Services\SendGridEmailService;
use InovantiBank\Messaging\Services\TwilioSmsService;
use InovantiBank\Messaging\Services\TwilioWhatsAppService;

class MessagingServiceProvider extends ServiceProvider
{
    /**
     * Registra os serviços de mensagens no container do Laravel.
     */
    public function register()
    {
        $this->app->singleton(MessageService::class, function ($app) {
            return new MessageService([
                'sms' => $app->make(TwilioSmsService::class),
                'whatsapp' => $app->make(TwilioWhatsAppService::class),
                'email' => $app->make(SendGridEmailService::class),
            ], $app->make(Dispatcher::class));
        });

        $this->app->singleton(TwilioSmsService::class, function ($app) {
            return new TwilioSmsService(new TwilioProvider(
                config('messaging.twilio.account_sid'),
                config('messaging.twilio.auth_token')
            ));
        });

        $this->app->singleton(TwilioWhatsAppService::class, function ($app) {
            return new TwilioWhatsAppService(new TwilioProvider(
                config('messaging.twilio.account_sid'),
                config('messaging.twilio.auth_token')
            ));
        });

        $this->app->singleton(SendGridEmailService::class, function ($app) {
            return new SendGridEmailService(new SendGridProvider(
                config('messaging.sendgrid.api_key')
            ));
        });
    }

    /**
     * Publica o arquivo de configuração.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../Messaging/Config/messaging.php' => config_path('messaging.php'),
        ], 'config');
    }
}
