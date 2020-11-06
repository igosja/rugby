<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Country
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 *
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
            ['/federation/team', $this->id]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['country_id' => 'id']);
    }
}
