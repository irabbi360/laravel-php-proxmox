<?php

namespace Irabbi360\Proxmox\Exception;

class ProxmoxRequestException extends \Exception
{
    public function __construct(string $message, string $code, private string $method, private string $url, private string $status, private string $body)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
