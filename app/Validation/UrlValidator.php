<?php

namespace App\Validation;

use App\Message\Message;

class UrlValidator extends Message implements UrlValidatorInterface
{
    private array $validators;

    public function __construct(array $validators) {
        $this->validators = $validators;
    }

    public function isValid(string $url): bool
    {
        /** @var $validator UrlValidatorInterface */
        foreach ($this->validators as $validator) {
            if (!$validator->isValid($url)) {
                $this->setMessage($validator->getMessage());
                return false;
            }
        }
        return true;
    }
}