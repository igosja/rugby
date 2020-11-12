<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Style
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Style extends AbstractActiveRecord
{
    public const NORMAL = 1;
    public const DOWN_THE_MIDDLE = 2;
    public const CHAMPAGNE = 3;
    public const MAN_10 = 4;
    public const MAN_15 = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%style}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 15],
            [['name'], 'unique'],
        ];
    }
}
