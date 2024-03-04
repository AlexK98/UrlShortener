<?php

namespace App\Validation;

use App\Message\Message;

class CanBeParsedValidator extends Message implements UrlValidatorInterface
{
    public function isValid(string $url): bool
    {
        $parsedUrl = parse_url(trim($url));

        if (!$parsedUrl || !isset($parsedUrl['scheme']) || !isset($parsedUrl['host'])) {
            $this->setMessage('Could not parse Url.');
            return false;
        }

        return true;
    }
}