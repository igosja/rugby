<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Rule
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $order
 * @property string $text
 * @property string $title
 */
class Rule extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rule}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['order', 'title', 'text'], 'required'],
            [['order'], 'min' => 1, 'max' => 99],
            [['title', 'text'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['text'], 'string'],
        ];
    }
}
