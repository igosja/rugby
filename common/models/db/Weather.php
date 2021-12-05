<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Weather
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Weather extends AbstractActiveRecord
{
    public const VERY_HOT = 1;
    public const HOT = 2;
    public const SUNNY = 3;
    public const CLOUDY = 4;
    public const RAIN = 5;
    public const SNOW = 6;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%weather}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getRandomWeatherId(): int
    {
        $array = [];
        for ($i = 0; $i < 1; $i++) {
            $array[] = self::VERY_HOT;
            $array[] = self::SNOW;
        }
        for ($i = 0; $i < 5; $i++) {
            $array[] = self::HOT;
            $array[] = self::RAIN;
        }
        for ($i = 0; $i < 10; $i++) {
            $array[] = self::SUNNY;
            $array[] = self::CLOUDY;
        }
        return $array[array_rand($array)];
    }
}
