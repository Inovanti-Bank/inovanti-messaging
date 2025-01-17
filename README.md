# Componente Messaging para aplicação em Laravel

[![Latest Stable Version](https://poser.pugx.org/inovanti-bank/inovanti-messaging/v)](//packagist.org/packages/inovanti-bank/messaging)
[![Total Downloads](https://poser.pugx.org/inovanti-bank/inovanti-messaging/downloads)](//packagist.org/packages/inovanti-bank/messaging)
[![License](https://poser.pugx.org/inovanti-bank/inovanti-messaging/license)](//packagist.org/packages/inovanti-bank/messaging)

Pacote desenvolvido para facilitar a troca de mensagens e integração com serviços externos de envio (SMS, e-mail, notificações, etc.) em projetos Laravel 11. O objetivo é fornecer uma API simples para gerenciar provedores de envio, rastreamento de mensagens e logs, sem complicar o fluxo de desenvolvimento.

## Índice

1. [Instalação](#instalação)
2. [Configuração](#configuração)
3. APIs Suportadas
4. [Uso](#uso)
   - [Exemplo Básico](#exemplo-básico)
5. [Testes](#testes)
6. [Contribuindo](#contribuindo)
7. [Licença](#licença)

---

## Instalação

Para instalar este pacote via [Composer](https://getcomposer.org/), utilize o seguinte comando:

```bash
composer require inovanti-bank/messaging
```

## Configuração

### Service Provider

Se você estiver usando o Laravel 11, o próprio framework já pode descobrir o provider e o facade automaticamente. Porém, caso queira registrar manualmente, adicione no array de providers do arquivo `config/app.php`:

```php
'providers' => [
    // ...
    InovantiBank\Messaging\Providers\MessagingServiceProvider::class,
],
```

## Publicar Configurações (opcional)

Este pacote pode conter um arquivo de configuração que você pode publicar para customizar:

```bash
php artisan vendor:publish --provider="InovantiBank\Messaging\Providers\MessagingServiceProvider" --tag="config"
```

Após isso, edite o arquivo `config/messaging.php` conforme necessário.

Adicione as seguintes variáveis no `.env`:

```bash
# Twilio
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_SMS_FROM=+1234567890
TWILIO_WHATSAPP_FROM=+1234567890

# SendGrid
SENDGRID_API_KEY=your_sendgrid_api_key
SENDGRID_FROM_EMAIL=no-reply@seu-dominio.com
```

## APIs Suportadas

Atualmente, a versão `1.0.0` do `inovanti-messaging` suporta as seguintes plataformas de envio de mensagens:

- **Twilio** (SMS, WhatsApp)
- **SendGrid** (E-mail)

Estamos constantemente adicionando novos provedores de envio ao pacote. Para obter a lista mais atualizada das APIs suportadas e instruções sobre como configurá-las, consulte o [repositório no GitHub](https://github.com/Inovanti-Bank/inovanti-messaging).

Se houver suporte a novos provedores, a documentação será atualizada para incluir instruções específicas sobre como utilizá-los.

## Uso

### Exemplo Básico

Após instalar e configurar, você pode começar a enviar mensagens usando a facade `Messaging` (ou a injeção de dependência via construtor, se preferir):

```php
use InovantiBank\Messaging\Services\MessageService;
use InovantiBank\Messaging\DTOs\MessageData;

$config = config('messaging');
$messageService = new MessageService($config);

$messageData = new MessageData(
    type: 'email',
    to: 'destinatario@example.com',
    from: config('messaging.sendgrid.from_email'),
    content: 'Este é um e-mail de teste enviado via SendGrid.',
    metadata: ['subject' => 'Teste de E-mail via SendGrid']
);

$response = $messageService->send($messageData);

print_r($response);
```

### Neste exemplo:

- Montamos um objeto `MessageData` com os campos necessários para o envio da mensagem.
- Chamamos o método `send` do serviço de mensagens.
- Tratamos o objeto de retorno que informa se o envio foi bem-sucedido.

> Dica: As configurações de provedor de envio, tokens de autenticação e endpoints podem ser gerenciadas no arquivo de configuração publicado, caso seu serviço exija.

## Testes

O pacote vem com testes unitários e de integração simulada para garantir que tudo funcione conforme o esperado. Você pode executar os testes usando PHPUnit:

```bash
vendor/bin/phpunit
```

### Para testes unit:

```bash
vendor/bin/phpunit --testsuite=Unit
```

### Para testes de integração:

```bash
vendor/bin/phpunit --testsuite=Integration
```

## Contribuindo

Contribuições são bem-vindas! Se você deseja reportar um bug, solicitar um novo recurso ou contribuir com código, fique à vontade para abrir uma issue ou enviar um Pull Request.

1. Faça um Fork do projeto
2. Crie sua feature branch: `git checkout -b minha-nova-feature`
3. Commit suas mudanças: `git commit -m 'Adiciona nova feature'`
4. Faça o push para a branch: `git push origin minha-nova-feature`
5. Crie um novo Pull Request

## Licença

Este projeto está licenciado sob a [MIT license](https://github.com/Inovanti-Bank/inovanti-messaging/tree/production?tab=MIT-1-ov-file).
