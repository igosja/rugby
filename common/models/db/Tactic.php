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
