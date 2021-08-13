<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class DealReason
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class DealReason extends AbstractActiveRecord
{
    public const MANAGER_LIMIT = 1;
    public const TEAM_LIMIT = 2;
    public const NO_MONEY = 3;
    public const NOT_BEST = 4;
    public const REFERRER = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%deal_reason}}';
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
            [['text'], 'unique'],
        ];
    }
}
