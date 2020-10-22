<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserRole
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class UserRole extends AbstractActiveRecord
{
    public const ADMIN = 5;
    public const USER = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_role}}';
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
        ];
    }
}
