<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class MoneyText
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class MoneyText extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%money_text}}';
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
            [['text'], 'unique'],
        ];
    }
}
