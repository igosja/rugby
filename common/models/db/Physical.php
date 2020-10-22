<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Physical
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $opposite_id
 * @property int $value
 *
 * @property-read Physical $opposite
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
            [['name', 'opposite_id', 'value'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 20],
            [['opposite_id'], 'integer', 'min' => 1, 'max' => 99],
            [['value'], 'integer', 'min' => -999, 'max' => 999],
            [['opposite_id'], 'exist', 'targetRelation' => 'opposite'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOpposite(): ActiveQuery
    {
        return $this->hasOne(self::class, ['id' => 'opposite_id']);
    }
}
