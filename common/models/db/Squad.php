<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Squad
 * @package common\models\db
 *
 * @property int $id
 * @property string $color
 * @property string $name
 */
class Squad extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%squad}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['color', 'name'], 'required'],
            [['color', 'name'], 'trim'],
            [['color'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 255],
            [['color', 'name'], 'unique'],
        ];
    }
}
