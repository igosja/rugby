<?php

// TODO refactor

namespace common\components\helpers;

use DateTimeZone;

/**
 * Class TimeZoneHelper
 * @package common\components
 */
class TimeZoneHelper
{
    /**
     * @return array
     */
    public static function zoneList(): array
    {
        $result = [];
        $list = DateTimeZone::listIdentifiers();
        foreach ($list as $item) {
            $result[$item] = $item;
        }
        return $result;
    }
}