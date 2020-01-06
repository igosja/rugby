<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Season
 * @package common\models\db
 *
 * @property int $season_id
 */
class Season extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%season}}';
    }

    /**
     * @param bool $cache
     * @return int
     */
    public static function getCurrentSeason(bool $cache = true): int
    {
        $query = self::find()
            ->select(['season_id'])
            ->orderBy(['season_id' => SORT_DESC]);
        if ($cache) {
            $query->cache();
        } else {
            $query->noCache();
        }
        return $query->scalar();
    }

    /**
     * @return array
     */
    public static function getSeasonArray(): array
    {
        $result = self::find()
            ->select(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($result, 'season_id', 'season_id');
    }
}
