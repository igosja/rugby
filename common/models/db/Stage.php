<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Stage
 * @package common\models\db
 *
 * @property int $stage_id
 * @property string $stage_name
 * @property int $stage_visitor
 */
class Stage extends AbstractActiveRecord
{
    const FRIENDLY = 1;
    const TOUR_1 = 2;
    const TOUR_2 = 3;
    const TOUR_3 = 4;
    const TOUR_4 = 5;
    const TOUR_5 = 6;
    const TOUR_6 = 7;
    const TOUR_7 = 8;
    const TOUR_8 = 9;
    const TOUR_9 = 10;
    const TOUR_10 = 11;
    const TOUR_11 = 12;
    const TOUR_12 = 13;
    const TOUR_13 = 14;
    const TOUR_14 = 15;
    const TOUR_15 = 16;
    const TOUR_16 = 17;
    const TOUR_17 = 18;
    const TOUR_18 = 19;
    const TOUR_19 = 20;
    const TOUR_20 = 21;
    const TOUR_21 = 22;
    const TOUR_22 = 23;
    const TOUR_23 = 24;
    const TOUR_24 = 25;
    const TOUR_25 = 26;
    const TOUR_26 = 27;
    const TOUR_27 = 28;
    const TOUR_28 = 29;
    const TOUR_29 = 30;
    const TOUR_30 = 31;
    const QUALIFY_1 = 32;
    const QUALIFY_2 = 33;
    const QUALIFY_3 = 34;
    const TOUR_LEAGUE_1 = 35;
    const TOUR_LEAGUE_2 = 36;
    const TOUR_LEAGUE_3 = 37;
    const TOUR_LEAGUE_4 = 38;
    const TOUR_LEAGUE_5 = 39;
    const TOUR_LEAGUE_6 = 40;
    const ROUND_OF_16 = 41;
    const QUARTER = 42;
    const SEMI = 43;
    const FINAL_GAME = 44;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%stage}}';
    }
}
