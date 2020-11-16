<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Physical
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $opposite_physical_id
 * @property int $value
 *
 * @property-read Physical $oppositePhysical
 */
class Physical extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%physical}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'opposite_physical_id', 'value'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 20],
            [['opposite_id'], 'integer', 'min' => 1, 'max' => 99],
            [['value'], 'integer', 'min' => -999, 'max' => 999],
            [['opposite_id'], 'exist', 'targetRelation' => 'opposite'],
            [['name', 'opposite_physical_id'], 'unique'],
        ];
    }

    /**
     * @return string
     */
    public function image(): string
    {
        return Html::img(
            '/img/physical/' . $this->id . '.png',
            [
                'alt' => $this->name,
                'title' => $this->name,
            ]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getOppositePhysical(): ActiveQuery
    {
        return $this->hasOne(self::class, ['id' => 'opposite_physical_id']);
    }
}
