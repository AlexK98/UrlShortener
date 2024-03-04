<?php

namespace App\Validation;

use App\Config\Consts;
use App\Message\Message;

class LengthValidator extends Message implements UrlValidatorInterface
{
    public function isValid(string $url): bool
    {
        if (strlen($url) > Consts::URL_MAX_LENGTH) {
            $this->setMessage('Url exceeds set limits');
            return false;
        }

        return true;
    }
}