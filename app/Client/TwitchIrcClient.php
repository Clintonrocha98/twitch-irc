<?php

namespace App\Client;

use App\Contract\IrcClientContract;
use RuntimeException;

class TwitchIrcClient implements IrcClientContract
{
    private $socket;

    public function __construct(
        private readonly string $server,
        private readonly int $port,
        private readonly string $token,
        private readonly string $nick,
        private readonly string $channel,
    ) {
    }

    public function connect(): void
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ]
        ]);

        $this->socket = stream_socket_client(
            "tls://{$this->server}:{$this->port}",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$this->socket) {
            throw new RuntimeException($errstr, $errno);
        }

        $this->send("PASS {$this->token}");
        $this->send("NICK {$this->nick}");
        $this->send("CAP REQ :twitch.tv/tags twitch.tv/commands twitch.tv/membership");
        $this->send("JOIN #{$this->channel}");
    }

    public function listen(callable $onMessage): void
    {
        while (!feof($this->socket)) {
            $line = trim(fgets($this->socket));

            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, 'PING')) {
                $this->send("PONG :tmi.twitch.tv");
                continue;
            }

            $onMessage($line);
        }
    }

    public function disconnect(): void
    {
        fclose($this->socket);
    }

    private function send(string $command): void
    {
        fwrite($this->socket, $command."\r\n");
    }
}
