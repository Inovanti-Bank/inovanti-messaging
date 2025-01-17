<?php

namespace InovantiBank\Messaging\Providers;

use Illuminate\Support\ServiceProvider;
use InovantiBank\Messaging\Services\MessageService;
use InovantiBank\Messaging\Services\TwilioSmsService;
use InovantiBank\Messaging\Services\TwilioWhatsAppService;
use InovantiBank\Messaging\Services\TwilioEmailService;
use InovantiBank\Messaging\Providers\TwilioProvider;
use InovantiBank\Messaging\Providers\SendGridProvider;
use Illuminate\Events\Dispatcher;

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
                'email' => $app->make(TwilioEmailService::class),
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

        $this->app->singleton(TwilioEmailService::class, function ($app) {
            return new TwilioEmailService(new SendGridProvider(
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
            __DIR__.'/../Config/messaging.php' => config_path('messaging.php'),
        ], 'config');
    }
}
