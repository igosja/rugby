<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BaseScout
 * @package common\models\db
 *
 * @property int $base_scout_id
 * @property int $base_scout_base_level
 * @property int $base_scout_build_speed
 * @property int $base_scout_distance
 * @property int $base_scout_level
 * @property int $base_scout_market_game_row
 * @property int $base_scout_market_physical
 * @property int $base_scout_market_tire
 * @property int $base_scout_my_style_count
 * @property int $base_scout_my_style_price
 * @property int $base_scout_opponent_game_row
 * @property int $base_scout_opponent_physical
 * @property int $base_scout_opponent_tire
 * @property int $base_scout_price_buy
 * @property int $base_scout_price_sell
 * @property int $base_scout_scout_speed_max
 * @property int $base_scout_scout_speed_min
 */
class BaseScout extends AbstractActiveRecord
{
    const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_scout}}';
    }

    /**
     * @return bool
     */
    public function canSeeDealGameRow(): bool
    {
        if ($this->base_scout_level >= 1) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canSeeDealTire(): bool
    {
        if ($this->base_scout_level >= 2) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canSeeDealPhysical(): bool
    {
        if ($this->base_scout_level >= 3) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canSeeOpponentGameRow(): bool
    {
        if ($this->base_scout_level >= 4) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canSeeOpponentTire(): bool
    {
        if ($this->base_scout_level >= 5) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canSeeOpponentPhysical(): bool
    {
        if ($this->base_scout_level >= 6) {
            return true;
        }
        return false;
    }
}
