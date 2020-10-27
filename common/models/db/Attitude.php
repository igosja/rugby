<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Attitude
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 */
class Attitude extends AbstractActiveRecord
{
    public const NEGATIVE = 1;
    public const NEUTRAL = 2;
    public const POSITIVE = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%attitude}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'order'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['order'], 'integer', 'min' => 1, 'max' => 9],
            [['name', 'order'], 'unique'],
        ];
    }
}
