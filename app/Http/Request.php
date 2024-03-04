<?php

namespace App\Http;

class Request
{
    private array $post;
    private array $server;

    public function __construct(array $post, array $server)
    {
        $this->post = $post;
        $this->server = $server;
    }

    public static function createFromGlobals(): self
    {
        return new self($_POST, $_SERVER);
    }

    public function getScheme(): string
    {
        return $this->server['REQUEST_SCHEME'] . '://';
    }

    public function getHost(): string
    {
        return $this->server['SERVER_NAME'];
    }

    public function postGetUrl(): string
    {
        return $this->post['url'] ?? '';
    }

    public function serverGetUri(): string
    {
        return explode('?', $this->server['REQUEST_URI'])[0];
    }

    public function isMethodPost(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST' || $this->server['REQUEST_METHOD'] === 'post';
    }

    public function isMethodGet(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'GET' || $this->server['REQUEST_METHOD'] === 'get';
    }

    public function redirectTo(string $url): void
    {
        header("Location: $url");
    }
}
