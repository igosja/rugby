<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ConstructionType
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class ConstructionType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%construction_type}}';
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
