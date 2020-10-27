<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Country
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Country extends AbstractActiveRecord
{
    public const DEFAULT_ID = 54;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%country}}';
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
