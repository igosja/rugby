<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Country
 * @package common\models\db
 *
 * @property int $country_id
 * @property string $country_name
 *
 * @property City[] $cities
 * @property Federation $federation
 */
class Country extends AbstractActiveRecord
{
    const DEFAULT_ID = 54;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%country}}';
    }

    /**
     * @return string
     */
    public function countryImage(): string
    {
        return Html::img(
            '/img/country/12/' . $this->country_id . '.png',
            [
                'alt' => $this->country_name,
                'title' => $this->country_name,
                'style' => [
                    'position' => 'relative',
                    'top' => '1px',
                ],
            ]
        );
    }

    /**
     * @return string
     */
    public function countryImageLink(): string
    {
        return Html::a($this->countryImage(), ['federation/news', 'id' => $this->country_id]);
    }

    /**
     * @return string
     */
    public function countryLink(): string
    {
        return $this->countryImage() . ' ' . Html::a($this->country_name, ['federation/news', 'id' => $this->country_id]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCities(): ActiveQuery
    {
        return $this->hasMany(City::class, ['city_country_id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['federation_country_id' => 'country_id']);
    }
}
