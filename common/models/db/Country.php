<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Country
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 *
 * @property-read City[] $cities
 * @property-read Federation $federation
 */
class Country extends AbstractActiveRecord
{
    public const DEFAULT_ID = 54;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%country}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return array
     */
    public static function selectOptions(): array
    {
        return ArrayHelper::map(
            self::find()->where(['!=', 'id', 0])->orderBy(['name' => SORT_ASC])->all(),
            'id',
            'name'
        );
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return Html::img(
            '/img/country/12/' . $this->id . '.png',
            [
                'alt' => $this->name,
                'title' => $this->name,
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
    public function getImageLink(): string
    {
        return $this->getLink(
            Html::img('@country12/' . $this->id . '.png')
        );
    }

    /**
     * @return string
     */
    public function getImageTextLink(): string
    {
        return $this->getLink(
            Html::img('@country12/' . $this->id . '.png') . ' ' . $this->name
        );
    }

    /**
     * @return string
     */
    public function getTextLink(): string
    {
        return $this->getLink($this->name);
    }

    /**
     * @param string $text
     * @return string
     */
    private function getLink(string $text): string
    {
        return Html::a(
            $text,
            ['/federation/team', 'id' => $this->id]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getCities(): ActiveQuery
    {
        return $this->hasMany(City::class, ['country_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['country_id' => 'id']);
    }
}
