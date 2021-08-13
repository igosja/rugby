<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class NationalType
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class NationalType extends AbstractActiveRecord
{
    public const MAIN = 1;
    public const U21 = 2;
    public const U19 = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national_type}}';
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
