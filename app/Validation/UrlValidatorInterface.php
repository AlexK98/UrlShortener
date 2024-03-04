<?php

namespace App\Validation;

interface UrlValidatorInterface
{
    public function isValid(string $url): bool;
}