<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FriendlyStatus
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class FriendlyStatus extends AbstractActiveRecord
{
    public const ALL = 1;
    public const NONE = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%friendly_status}}';
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
