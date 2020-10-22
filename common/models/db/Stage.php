<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Stage
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $visitor
 */
class Stage extends AbstractActiveRecord
{
    public const FRIENDLY = 1;
    public const TOUR_1 = 2;
    public const TOUR_2 = 3;
    public const TOUR_3 = 4;
    public const TOUR_4 = 5;
    public const TOUR_5 = 6;
    public const TOUR_6 = 7;
    public const TOUR_7 = 8;
    public const TOUR_8 = 9;
    public const TOUR_9 = 10;
    public const TOUR_10 = 11;
    public const TOUR_11 = 12;
    public const TOUR_12 = 13;
    public const TOUR_13 = 14;
    public const TOUR_14 = 15;
    public const TOUR_15 = 16;
    public const TOUR_16 = 17;
    public const TOUR_17 = 18;
    public const TOUR_18 = 19;
    public const TOUR_19 = 20;
    public const TOUR_20 = 21;
    public const TOUR_21 = 22;
    public const TOUR_22 = 23;
    public const TOUR_23 = 24;
    public const TOUR_24 = 25;
    public const TOUR_25 = 26;
    public const TOUR_26 = 27;
    public const TOUR_27 = 28;
    public const TOUR_28 = 29;
    public const TOUR_29 = 30;
    public const TOUR_30 = 31;
    public const QUALIFY_1 = 32;
    public const QUALIFY_2 = 33;
    public const QUALIFY_3 = 34;
    public const TOUR_LEAGUE_1 = 35;
    public const TOUR_LEAGUE_2 = 36;
    public const TOUR_LEAGUE_3 = 37;
    public const TOUR_LEAGUE_4 = 38;
    public const TOUR_LEAGUE_5 = 39;
    public const TOUR_LEAGUE_6 = 40;
    public const ROUND_OF_16 = 41;
    public const QUARTER = 42;
    public const SEMI = 43;
    public const FINAL_GAME = 44;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%stage}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'visitor'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 25],
            [['visitor'], 'integer', 'min' => 1, 'max' => 999],
        ];
    }
}
