<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Sex
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Sex extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%sex}}';
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
        ];
    }
}
