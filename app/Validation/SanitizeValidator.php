<?php

namespace App\Validation;

use App\Message\Message;

class SanitizeValidator extends Message implements UrlValidatorInterface
{
    public function isValid(string $url): bool
    {
        // URL should be preprocessed with idn_to_ascii() otherwise it will fail with non-ASCII url
        $value = filter_var($url, FILTER_SANITIZE_URL);

        if ($value !== $url) {
            $this->setMessage('Something wrong with Url');
            return false;
        }

        return true;
    }
}