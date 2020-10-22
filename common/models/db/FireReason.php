<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FireReason
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class FireReason extends AbstractActiveRecord
{
    public const FIRE_REASON_SELF = 1;
    public const FIRE_REASON_AUTO = 2;
    public const FIRE_REASON_ABSENCE = 3;
    public const FIRE_REASON_PENALTY = 4;
    public const FIRE_REASON_EXTRA_TEAM = 5;
    public const FIRE_REASON_NEW_SEASON = 6;
    public const FIRE_REASON_VOTE = 7;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%fire_reason}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
        ];
    }
}
