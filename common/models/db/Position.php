<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Position
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property string $text
 */
class Position extends AbstractActiveRecord
{
    public const POS_01 = 1;
    public const POS_02 = 2;
    public const POS_03 = 3;
    public const POS_04 = 4;
    public const POS_05 = 5;
    public const POS_06 = 6;
    public const POS_07 = 7;
    public const POS_08 = 8;
    public const POS_09 = 9;
    public const POS_10 = 10;
    public const POS_11 = 11;
    public const POS_12 = 12;
    public const POS_13 = 13;
    public const POS_14 = 14;
    public const POS_15 = 15;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%position}}';
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
            [['name', 'text'], 'unique'],
        ];
    }
}
