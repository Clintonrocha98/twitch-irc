# IRC Twitch

POC para estudar o protocolo IRC aplicado ao chat da Twitch, com foco em parsing, fluxo de eventos e organização em um monolito modular.

## Quickstart
1. Clone o repositório e instale dependências:

```bash
git clone <repo-url>
cd <repo-folder>
composer install
```

2. Copie o `.env` e ajuste as variáveis necessárias:

```bash
cp .env.example .env
```

3. Execute as migrations (quando for necessário persistir dados):

```bash
php artisan migrate
```

4. Inicie o bot/CLI:

```bash
php artisan app:irc
```

## Requisitos
- PHP 8.2+
- Composer
- Extensões PHP recomendadas: `openssl`, `mbstring`
- Conta na Twitch e token OAuth com escopo `chat:read`

## Instalação

```bash
composer install
```

```bash
cp .env.example .env
```
Ajuste as variáveis (`TWITCH_*`) conforme explicado abaixo

## Configuração
No arquivo `.env` configure as variáveis relacionadas ao IRC da Twitch:

```env
TWITCH_IRC_SERVER=irc.chat.twitch.tv
TWITCH_IRC_PORT=6697
TWITCH_IRC_TOKEN=oauth:SEU_TOKEN_AQUI
TWITCH_IRC_NICK=seu_usuario
TWITCH_IRC_CHANNEL=#nome_do_canal
```

As configurações também podem ser encontradas/ajustadas em `config/twitch.php`.

## Executando (Start)
- Rodar localmente com o comando Artisan:

```bash
php artisan app:irc
```

## Testes
- Comando padrão (Laravel):

```bash
php artisan test
```

- Path dos testes deste projeto (módulos):
  - `app-modules/twitch-irc/tests`
  - `app-modules/chat/tests`

## Estrutura do Projeto
- `app/` — integração com Laravel (comandos, providers)
- `app-modules/` — módulos organizados como pacotes locais
  - `app-modules/chat` — regras de chat, eventos, comandos, persistência
  - `app-modules/twitch-irc` — cliente IRC, parser, transformer
- `config/` — arquivos de configuração

## Adicionando comandos de chat
1. Crie a classe do comando em:

```
app-modules/chat/src/Commands
```

2. Implemente a interface `ChatCommand` (veja `app-modules/chat/src/Contracts/ChatCommand.php`).

3. Registre o comando no `ChatServiceProvider` (exemplo):

```php
$this->app->tag([
    RankChatCommand::class,
    // Novo comando aqui
], ChatCommand::class, 'chat-commands');
```

Após o registro, o comando será detectado automaticamente pelo sistema de comando do projeto.

## Arquitetura e fluxo
Fluxo geral (ETC/ETL):

1. O client IRC (`TwitchIrcClient`) recebe uma linha do socket
2. A linha é processada pelo parser bruto (`RawMessageParser`) que extrai tags, prefix, command, params e text
3. O transformer (`ChatMessageTransformer`) converte a estrutura bruta em um `ChatMessage` de domínio
4. O evento `ChatMessageReceived` é disparado
5. Listeners atuam sobre o evento para persistência, execução de comandos de chat e outras regras (XP, etc.)

Principais arquivos:
- `app-modules/twitch-irc/src/Client/TwitchIrcClient.php`
- `app-modules/twitch-irc/src/Parser/RawMessageParser.php`
- `app-modules/twitch-irc/src/Transformer/ChatMessageTransformer.php`
- `app-modules/chat/src/Events/ChatMessageReceived.php`

## Ferramentas e qualidade de código
- Formatação: `make pint` (Laravel Pint)
- Refatoração: `make rector` (Rector)

Recomenda-se rodar `make pint` antes de abrir PRs de código.

## Troubleshooting
- Token inválido / autenticação falha: verifique o valor em `.env` e se o token possui o escopo correto (`chat:read`).
- TLS/porta: use `6697` para TLS (ver `TWITCH_IRC_PORT`).
- Parser não retornando tags: certifique-se de que as capabilities foram solicitadas corretamente pelo cliente (CAP REQ).

---

