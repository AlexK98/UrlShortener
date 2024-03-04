<?php

namespace App\Validation;

use App\Message\Message;

class SchemeValidator extends Message implements UrlValidatorInterface
{
    public function isValid(string $url): bool
    {
        $parsedUrl = parse_url(trim($url));

        $scheme = $this->stripXn($parsedUrl['scheme']);

        if ($scheme !== 'http' && $scheme !== 'https') {
            $this->setMessage('Only URLs with HTTP or HTTPS are allowed.');
            return false;
        }

        return true;
    }

    private function stripXn(string $scheme): string
    {
        $prefix = 'xn--';

        if (strpos($scheme, $prefix) === 0) {
            return substr($scheme, strlen($prefix));
        }

        return $scheme;
    }
}