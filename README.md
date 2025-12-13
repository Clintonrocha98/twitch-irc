# IRC Twitch

Este projeto é uma POC para entender o funcionamento do protocolo IRC aplicado ao chat da Twitch, com foco parsing de protocolo e organização de código (ETC / parsing em camadas).

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

Para gerar o token, foi utilizado o site: [twitchtokengenerator](https://twitchtokengenerator.com)

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
* processar e exibir as mensagens no terminal

---

## Dúvidas durante o desenvolvimento

### O que é ETL (ou ETC)

No contexto do projeto, o fluxo segue o conceito de:

* **Extract**: leitura da linha bruta vinda do socket IRC
* **Transform**: conversão da mensagem bruta em uma estrutura compreensível pelo domínio
* **Consume**: uso da mensagem já interpretada (exibir no terminal, bot, estatísticas, etc.)

No código isso se reflete em:

* `RawParser` → extrai partes do protocolo
* `MessageTransformer` → converte para entidade de domínio
* `Message` / `UserInfo` → entidades usadas pela aplicação

---

### RFC do protocolo IRC

O protocolo base utilizado é descrito na RFC oficial: [rfc1459](https://www.rfc-editor.org/rfc/rfc1459)

A Twitch utiliza IRC como base, mas adiciona **extensões próprias** (tags, comandos e eventos adicionais).

---

### Como estruturar o código

A estrutura adotada separa claramente responsabilidades:

* Client IRC → cuida da conexão e do protocolo
* Parser → extrai dados da string
* Transformer → aplica regras de domínio
* Entidades → representam conceitos do chat
* Command Laravel → apenas orquestra o uso

Essa separação evita acoplamento excessivo e facilita testes e evolução.

---

## O necessário para conectar

Para se conectar ao IRC da Twitch são necessários:

* URL do servidor IRC
* Porta
* Token OAuth
* Nick (usuário da Twitch)
* Canal

Esses dados não ficam hardcoded no client, mas são fornecidos via configuração (`.env`).

---

Abaixo estão **apenas os dois tópicos refatorados**, mantendo o restante do README inalterado.

---

## Solicitando capabilities da Twitch

Durante o estudo do protocolo IRC aplicado à Twitch, este foi **um dos pontos que gerou dúvida**, pois não faz parte do IRC “puro” definido na RFC 1459.
As *capabilities* são **extensões específicas da Twitch**, documentadas oficialmente pela plataforma.

Sem solicitar essas capabilities, o servidor IRC da Twitch retorna apenas mensagens básicas, sem metadados importantes.

Para habilitar informações adicionais, o client deve enviar:

```bash
CAP REQ :twitch.tv/membership twitch.tv/tags twitch.tv/commands
```

Essas capabilities permitem:

* `twitch.tv/membership`
  Receber eventos de JOIN, PART e lista de usuários no canal.

* `twitch.tv/tags`
  Receber metadados nas mensagens (badges, cor do usuário, id da mensagem, timestamp, etc.).

* `twitch.tv/commands`
  Receber comandos específicos da Twitch, como `CLEARCHAT`, `USERNOTICE`, `HOSTTARGET`.

Essas informações **não vêm da RFC**, mas sim da **documentação oficial da Twitch**.

Para aprofundar ou entender outras capabilities disponíveis, consulte: [Dev Twitch - IRC](https://dev.twitch.tv/docs/chat/irc/)

---

### Fazer JOIN no canal

Outro ponto que gerou dúvida durante o estudo foi o momento correto de executar o `JOIN`.

No fluxo da Twitch, o `JOIN` **deve acontecer somente após**:

* autenticação (`PASS` + `NICK`)
* solicitação das capabilities (opcional, mas recomendada)

O comando de entrada no canal é:

```text
JOIN #nome_do_canal
```

Observações importantes:

* o nome do canal deve estar em **minúsculas**
* sempre deve conter o prefixo `#`

Após o `JOIN`, o servidor começa a enviar as mensagens do chat e eventos relacionados ao canal.

Esse comportamento também é descrito na documentação oficial da Twitch: [Dev Twitch - IRC](https://dev.twitch.tv/docs/chat/irc/)

---

## Resumo do fluxo

* Cria o socket
* Conecta ao servidor IRC
* Envia PASS e NICK
* Solicita CAP REQ
* Recebe CAP ACK do servidor
* Executa JOIN no canal
* Recebe e processa mensagens do chat

Esse é o fluxo padrão de comunicação com o chat da Twitch via IRC.



