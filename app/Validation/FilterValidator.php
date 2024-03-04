<?php

namespace App\Validation;

use App\Message\Message;

class FilterValidator extends Message implements UrlValidatorInterface
{
    public function isValid(string $url): bool
    {
        if ((bool)filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->setMessage('Could not validate URL');
            return false;
        }

        return true;
    }
}