<?php

namespace App\Message;

abstract class Message
{
    private string $msg = '';

    public function setMessage(string $msg): void
    {
        $this->msg = $msg;
    }

    public function getMessage(): string
    {
        if (!empty($this->msg)) {
            return $this->msg . '<br>';
        }

        return $this->msg;
    }
}