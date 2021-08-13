<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Tactic
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Tactic extends AbstractActiveRecord
{
    public const ALL_DEFENCE = 1;
    public const DEFENCE = 2;
    public const NORMAL = 3;
    public const ATTACK = 4;
    public const ALL_ATTACK = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%tactic}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
        ];
    }
}
