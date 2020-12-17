<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FriendlyInviteStatus
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class FriendlyInviteStatus extends AbstractActiveRecord
{
    public const ACCEPTED = 2;
    public const CANCELED = 3;
    public const NEW_ONE = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%friendly_invite_status}}';
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
