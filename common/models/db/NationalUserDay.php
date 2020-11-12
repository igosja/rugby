<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class NationalUserDay
 * @package common\models\db
 *
 * @property int $id
 * @property int $day
 * @property int $national_id
 * @property int $season_id
 * @property int $user_id
 *
 * @property-read National $national
 * @property-read Season $season
 * @property-read User $user
 */
class NationalUserDay extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national_user_day}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['day', 'national_id', 'season_id', 'user_id'], 'required'],
            [['day', 'national_id', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['user_id'], 'integer', 'min' => 1],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
