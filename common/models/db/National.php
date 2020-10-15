<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class National
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property int $finance
 * @property int $mood_rest
 * @property int $mood_super
 * @property int $national_type_id
 * @property int $power_c_15
 * @property int $power_c_19
 * @property int $power_c_24
 * @property int $power_s_15
 * @property int $power_s_19
 * @property int $power_s_24
 * @property int $power_v
 * @property int $power_vs
 * @property int $stadium_id
 * @property int $user_id
 * @property int $vice_user_id
 * @property int $visitor
 *
 * @property-read Country $country
 * @property-read NationalType $nationalType
 * @property-read Stadium $stadium
 * @property-read User $user
 * @property-read User $viceUser
 */
class National extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'national_type_id'], 'required'],
            [['mood_rest', 'mood_super', 'national_type_id'], 'integer', 'min' => 1, 'max' => 9],
            [['country_id', 'visitor'], 'integer', 'min' => 1, 'max' => 999],
            [
                [
                    'power_c_15',
                    'power_c_19',
                    'power_c_24',
                    'power_s_15',
                    'power_s_19',
                    'power_s_24',
                    'power_v',
                    'power_vs',
                ],
                'integer',
                'min' => 1,
                'max' => 99999
            ],
            [['finance'], 'integer', 'min' => 0],
            [['stadium_id', 'user_id', 'vice_user_id'], 'integer', 'min' => 1],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['national_type_id'], 'exist', 'targetRelation' => 'nationalType'],
            [['stadium_id'], 'exist', 'targetRelation' => 'stadium'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
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
    public function getNationalType(): ActiveQuery
    {
        return $this->hasOne(NationalType::class, ['id' => 'national_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStadium(): ActiveQuery
    {
        return $this->hasOne(Stadium::class, ['id' => 'stadium_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getViceUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'vice_user_id']);
    }
}
