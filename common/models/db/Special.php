<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Special
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property string $text
 */
class Special extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%special}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'text'], 'required'],
            [['name', 'text'], 'trim'],
            [['name'], 'string', 'max' => 2],
            [['text'], 'string', 'max' => 255],
        ];
    }
}
