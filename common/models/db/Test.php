<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Test
 * @package common\models\db
 *
 * @property int $id
 * @property string $value
 */
class Test extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%test}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['value'], 'required'],
            [['value'], 'string'],
        ];
    }
}
