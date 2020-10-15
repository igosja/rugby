<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TransferPosition
 * @package common\models\db
 *
 * @property int $id
 * @property int $position_id
 * @property int $transfer_id
 *
 * @property-read Position $position
 * @property-read Transfer $transfer
 */
class TransferPosition extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_position}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['position_id', 'transfer_id'], 'required'],
            [['position_id'], 'integer', 'min' => 1, 'max' => 99],
            [['transfer_id'], 'integer', 'min' => 1],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
            [['transfer_id'], 'exist', 'targetRelation' => 'transfer'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['id' => 'transfer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }
}
