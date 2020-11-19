<?php

// TODO refactor

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
    public const ATHLETIC = 10;
    public const COMBINE = 3;
    public const IDOL = 11;
    public const LEADER = 9;
    public const MOUL = 8;
    public const PASS = 2;
    public const POWER = 1;
    public const RUCK = 7;
    public const SCRUM = 4;
    public const SPEED = 5;
    public const TACKLE = 6;

    public const MAX_LEVEL = 4;
    public const MAX_SPECIALS = 4;

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
            [['name', 'text'], 'unique'],
        ];
    }
}
