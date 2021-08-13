<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000105MoneyText
 * @package console\migrations
 */
class M200101000105MoneyText extends Migration
{
    private const TABLE = '{{%money_text}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                [Yii::t('console', 'migrations.money-text.income.add-funds')],
                [Yii::t('console', 'migrations.money-text.income.referral')],
                [Yii::t('console', 'migrations.money-text.outcome.team-finance')],
                [Yii::t('console', 'migrations.money-text.outcome.vip')],
                [Yii::t('console', 'migrations.money-text.income.friend')],
                [Yii::t('console', 'migrations.money-text.outcome.friend')],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
