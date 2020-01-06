<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class National
 * @package common\models\db
 *
 * @property int $national_id
 * @property int $national_country_id
 * @property int $national_finance
 * @property int $national_mood_rest
 * @property int $national_mood_super
 * @property int $national_national_type_id
 * @property int $national_power_c_21
 * @property int $national_power_c_26
 * @property int $national_power_c_32
 * @property int $national_power_s_21
 * @property int $national_power_s_26
 * @property int $national_power_s_32
 * @property int $national_power_v
 * @property int $national_power_vs
 * @property int $national_stadium_id
 * @property int $national_user_id
 * @property int $national_vice_id
 * @property int $national_visitor
 *
 * @property Country $country
 * @property NationalType $nationalType
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
     * @param bool $image
     * @return string
     */
    public function nationalLink(bool $image = false): string
    {
        $result = '';
        if ($image) {
            $result .= Html::img(
                    '/img/country/12/' . $this->country->country_id . '.png',
                    [
                        'alt' => $this->country->country_name,
                        'title' => $this->country->country_name,
                    ]
                ) . ' ';
        }
        $result .= Html::a(
            $this->country->country_name . ' (' . $this->nationalType->national_type_name . ')',
            ['national/view', 'id' => $this->national_id]
        );
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['country_id' => 'national_country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalType(): ActiveQuery
    {
        return $this->hasOne(NationalType::class, ['national_type_id' => 'national_national_type_id']);
    }
}
