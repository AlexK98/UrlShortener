<?php

namespace App\Config;

class Consts
{
    // SHORT URL GENERATOR
    const URL_MAX_LENGTH = 512;
    const HASH_MAX_LENGTH = 16;
    const MAX_HASHING_ATTEMPTS = 10;
    const HASH_STR_LENGTH = 9; // value should be bigger than BASE62_STR_LENGTH by at least 2 (chars)
    const BASE62_STR_LENGTH = 6;
    const BASE62 = 62;
    const BASE62_CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const BASE62_STR_LENGTH_FILLER = '0';
}
