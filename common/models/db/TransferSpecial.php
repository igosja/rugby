<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TransferSpecial
 * @package common\models\db
 *
 * @property int $id
 * @property int $level
 * @property int $special_id
 * @property int $transfer_id
 *
 * @property-read Special $special
 * @property-read Transfer $transfer
 */
class TransferSpecial extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_special}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['level', 'special_id', 'transfer_id'], 'required'],
            [['level'], 'integer', 'min' => 1, 'max' => 4],
            [['special_id'], 'integer', 'min' => 1, 'max' => 99],
            [['transfer_id'], 'integer', 'min' => 1],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
            [['transfer_id'], 'exist', 'targetRelation' => 'transfer'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['id' => 'special_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['id' => 'transfer_id']);
    }
}
