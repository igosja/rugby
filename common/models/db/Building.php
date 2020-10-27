<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Building
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Building extends AbstractActiveRecord
{
    public const BASE = 1;
    public const MEDICAL = 2;
    public const PHYSICAL = 3;
    public const SCHOOL = 4;
    public const SCOUT = 5;
    public const TRAINING = 6;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building}}';
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
