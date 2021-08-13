<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class MoneyText
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class MoneyText extends AbstractActiveRecord
{
    public const INCOME_ADD_FUNDS = 1;
    public const INCOME_FRIEND = 5;
    public const INCOME_REFERRAL = 2;
    public const OUTCOME_FRIEND = 6;
    public const OUTCOME_TEAM_FINANCE = 3;
    public const OUTCOME_VIP = 4;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%money_text}}';
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
