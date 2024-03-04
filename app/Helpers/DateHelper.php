<?php

namespace App\Helpers;

use DateTime;
use DateTimeZone;

class DateHelper
{
    public static function getDate(string $format): string
    {
        $dt = new DateTime('now');
        $dtz = new DateTimeZone('Europe/Kyiv');
        $dt->setTimezone($dtz);
        return $dt->format($format);
    }
}
