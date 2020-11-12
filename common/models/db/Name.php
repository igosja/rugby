<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Name
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Name extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%name}}';
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
}
