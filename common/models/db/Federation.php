<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Federation
 * @package common\models\db
 *
 * @property int $id
 * @property int $auto
 * @property int $country_id
 * @property int $finance
 * @property int $game
 * @property int $president_user_id
 * @property int $stadium_capacity
 * @property int $vice_user_id
 *
 * @property-read Country $country
 * @property-read User $presidentUser
 * @property-read User $viceUser
 */
class Federation extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%federation}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id'], 'required'],
            [['country_id'], 'integer', 'min' => 0, 'max' => 999],
            [['auto', 'game', 'stadium_capacity'], 'integer', 'min' => 0, 'max' => 99999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['president_user_id'], 'exist', 'targetRelation' => 'presidentUser'],
            [['vice_user_id'], 'exist', 'targetRelation' => 'viceUser'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPresidentUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'president_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getViceUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'vice_user_id']);
    }
}
