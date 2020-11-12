<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Mood
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Mood extends AbstractActiveRecord
{
    public const START_REST = 3;
    public const START_SUPER = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%mood}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 10],
            [['name'], 'unique'],
        ];
    }
}
