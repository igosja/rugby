<?php

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
        ];
    }
}
