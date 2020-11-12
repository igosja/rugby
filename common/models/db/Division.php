<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Division
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Division extends AbstractActiveRecord
{
    public const D1 = 1;
    public const D2 = 2;
    public const D3 = 3;
    public const D4 = 4;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%division}}';
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
