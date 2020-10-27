<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class DayType
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property string $text
 */
class DayType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%day_type}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'text'], 'required'],
            [['name', 'text'], 'trim'],
            [['name'], 'string', 'max' => 1],
            [['text'], 'string', 'max' => 255],
            [['name', 'text'], 'unique'],
        ];
    }
}
