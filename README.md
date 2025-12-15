# IRC Twitch

Este projeto é uma POC para entender o funcionamento do protocolo IRC aplicado ao chat da Twitch, com foco em parsing de protocolo, fluxo de eventos e organização de código em um **monolito modular**.

O objetivo principal é estudar:
- comunicação via IRC
- processamento de eventos em tempo real
- comandos de chat (ex: `!rank`)
- organização de código escalável, porém simples

---

## Requisitos

- PHP 8.2+
- Composer
- Laravel
- Conta na Twitch
- Token OAuth da Twitch com permissão de leitura de chat

---

## Instalação

Clone o repositório e instale as dependências:

```bash
composer install
````

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

Gere a key da aplicação Laravel:

```bash
php artisan key:generate
```

Executar migrações:

```bash
php artisan migrate
```

---

## Configuração do ambiente

No arquivo `.env`, configure as variáveis relacionadas ao IRC da Twitch:

```env
TWITCH_IRC_SERVER=irc.chat.twitch.tv
TWITCH_IRC_PORT=6697
TWITCH_IRC_TOKEN=oauth:SEU_TOKEN_AQUI
TWITCH_IRC_NICK=seu_usuario
TWITCH_IRC_CHANNEL=nome_do_canal
```

### Aviso importante sobre a Twitch

É obrigatório possuir um **token OAuth válido da Twitch** para conseguir autenticar no servidor IRC.

O token deve conter, no mínimo, o escopo:

* `chat:read`

Para gerar o token, foi utilizado o site:
[https://twitchtokengenerator.com](https://twitchtokengenerator.com)

O token gerado deve ser usado **com o prefixo `oauth:`**.

---

## Start do projeto

Para iniciar a conexão com o chat da Twitch via IRC:

```bash
php artisan app:irc
```

Esse comando irá:

* abrir a conexão TLS com o servidor IRC
* autenticar com a Twitch
* entrar no canal configurado
* escutar mensagens do chat
* processar eventos e comandos
* exibir mensagens e respostas no chat

---

## Fluxo de eventos e comandos

O projeto é orientado a **eventos**, e todo o fluxo acontece da seguinte forma:

1. O client IRC recebe uma linha do socket
2. A linha passa pelo parser e transformer
3. Uma `ChatMessage` é criada
4. O evento `ChatMessageReceived` é disparado
5. Listeners reagem ao evento:

    * persistem a mensagem
    * executam comandos de chat (`!rank`, etc)
    * calculam XP ou outras regras
6. Caso um comando seja reconhecido, o bot responde no chat via IRC

Os comandos de chat **não ficam no client** e **não ficam no listener**.
Eles são classes isoladas, registradas dinamicamente no container.

---

## Monolito modular

O projeto segue a ideia de **monolito modular**, ou seja:

* não é microserviço
* não é DDD
* é uma aplicação única, porém organizada por **contextos claros**

Cada módulo possui:

* responsabilidades bem definidas
* código isolado
* seu próprio `ServiceProvider`

Atualmente existem dois módulos principais:

* **chat**
  Responsável por:

    * regras de chat
    * eventos
    * comandos (`!rank`)
    * persistência de mensagens
    * cálculo de XP

* **twitch-irc**
  Responsável por:

    * conexão IRC
    * autenticação
    * parsing do protocolo
    * envio e recebimento de mensagens

---

## Criando novos comandos de chat

Os comandos de chat seguem uma interface comum (`ChatCommand`).

Para adicionar um novo comando:

1. Crie uma nova classe em:

   ```text
   app-modules/chat/src/Commands
   ```

2. Implemente a interface `ChatCommand`

3. Registre o comando no `ChatServiceProvider`:

```php
$this->app->tag([
    RankChatCommand::class,
    // Novo comando aqui
], ChatCommand::class, 'chat-commands');
```

Após isso, o comando passa a ser reconhecido automaticamente no chat.

---

## Makefile

O projeto possui um `Makefile` para padronização de código:

```bash
make pint
```

Executa o Laravel Pint para formatação de código.

```bash
make rector
```

Executa o Rector para refatorações automáticas e melhorias de código.

---

## Dúvidas durante o desenvolvimento

### O que é ETL (ou ETC)

No contexto do projeto, o fluxo segue o conceito de:

* **Extract**: leitura da linha bruta vinda do socket IRC
* **Transform**: conversão da mensagem bruta em uma estrutura compreensível
* **Consume**: persistência, comandos e respostas no chat

No código isso se reflete em:

* `IrcRawParser`
* `ChatMessageTransformer`
* Eventos e listeners do módulo Chat

---

### RFC do protocolo IRC

O protocolo base utilizado é descrito na RFC oficial:
[RFC1459](https://www.rfc-editor.org/rfc/rfc1459)

A Twitch utiliza IRC como base, mas adiciona **extensões próprias**.

---

## Solicitando capabilities da Twitch

Durante o estudo do protocolo IRC aplicado à Twitch, este foi um dos pontos que gerou dúvida.

As *capabilities* são extensões específicas da Twitch e devem ser solicitadas explicitamente:

```bash
CAP REQ :twitch.tv/membership twitch.tv/tags twitch.tv/commands
```

Essas capabilities habilitam:

* eventos de JOIN / PART
* metadados das mensagens
* comandos específicos da Twitch

Documentação oficial:
[Twitch Doc - IRC](https://dev.twitch.tv/docs/chat/irc/)

---

## Fazer JOIN no canal

O `JOIN` deve ocorrer somente após:

* autenticação (`PASS` + `NICK`)
* solicitação das capabilities

```text
JOIN #nome_do_canal
```

O nome do canal deve:

* estar em minúsculas
* conter o prefixo `#`

---

## Resumo do fluxo

* Cria o socket
* Conecta ao servidor IRC
* Envia PASS e NICK
* Solicita CAP REQ
* Recebe CAP ACK
* Executa JOIN
* Processa mensagens
* Dispara eventos
* Executa comandos de chat

