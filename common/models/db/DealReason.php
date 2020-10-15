<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class DealReason
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class DealReason extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%deal_reason}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
        ];
    }
}
