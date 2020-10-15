<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Language
 * @package common\models\db
 *
 * @property int $id
 * @property string $code
 * @property string $name
 */
class Language extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%language}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['code', 'name'], 'trim'],
            [['code'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 255],
        ];
    }
}
