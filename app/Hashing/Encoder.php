<?php

namespace App\Hashing;

use App\Config\Consts;

class Encoder
{
    public function encodeHash(string $hash): string
    {
        // Convert the hash to a decimal number
        $decimal = (int)hexdec($hash);

        return $this->base62Encode($decimal);
    }

    private function base62Encode(int $number): string
    {
        $characters = Consts::BASE62_CHARACTERS;
        $result = '';

        while ($number > 0) {
            $remainder = $number % Consts::BASE62;
            $result = $characters[$remainder] . $result;
            $number = floor($number / Consts::BASE62);
        }

        $resultLength = strlen($result);

        if ($resultLength > Consts::BASE62_STR_LENGTH) {
            $result = substr($result, 0, Consts::BASE62_STR_LENGTH);
        }

        if ($resultLength < Consts::BASE62_STR_LENGTH) {
            // Pad the result with leading zeros if necessary
            $padding = max(Consts::BASE62_STR_LENGTH - $resultLength, 0);
            $result = str_repeat(Consts::BASE62_STR_LENGTH_FILLER, $padding) . $result;
        }

        return $result;
    }
}
