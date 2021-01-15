<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000089FinanceText
 * @package console\migrations
 */
class M200101000089FinanceText extends Migration
{
    private const TABLE = '{{%finance_text}}';

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
                [Yii::t('console', 'migrations.finance-text.income.prize-vip')],
                [Yii::t('console', 'migrations.finance-text.income.prize-world-cup')],
                [Yii::t('console', 'migrations.finance-text.income.prize-league')],
                [Yii::t('console', 'migrations.finance-text.income.prize-championship')],
                [Yii::t('console', 'migrations.finance-text.income.prize-conference')],
                [Yii::t('console', 'migrations.finance-text.income.prize-off-season')],
                [Yii::t('console', 'migrations.finance-text.income.ticket')],
                [Yii::t('console', 'migrations.finance-text.outcome.game')],
                [Yii::t('console', 'migrations.finance-text.outcome.salary')],
                [Yii::t('console', 'migrations.finance-text.outcome.training-position')],
                [Yii::t('console', 'migrations.finance-text.outcome.training-special')],
                [Yii::t('console', 'migrations.finance-text.outcome.training-power')],
                [Yii::t('console', 'migrations.finance-text.outcome.scout-style')],
                [Yii::t('console', 'migrations.finance-text.outcome.building-stadium')],
                [Yii::t('console', 'migrations.finance-text.income.building-stadium')],
                [Yii::t('console', 'migrations.finance-text.outcome.building-base')],
                [Yii::t('console', 'migrations.finance-text.income.building-base')],
                [Yii::t('console', 'migrations.finance-text.income.transfer')],
                [Yii::t('console', 'migrations.finance-text.outcome.transfer')],
                [Yii::t('console', 'migrations.finance-text.income.transfer-first-team')],
                [Yii::t('console', 'migrations.finance-text.outcome.loan')],
                [Yii::t('console', 'migrations.finance-text.income.loan')],
                [Yii::t('console', 'migrations.finance-text.outcome.maintenance')],
                [Yii::t('console', 'migrations.finance-text.team.re-register')],
                [Yii::t('console', 'migrations.finance-text.income.pension')],
                [Yii::t('console', 'migrations.finance-text.income.national')],
                [Yii::t('console', 'migrations.finance-text.user.transfer')],
                [Yii::t('console', 'migrations.finance-text.income.referral')],
                [Yii::t('console', 'migrations.finance-text.federation.transfer')],
                [Yii::t('console', 'migrations.finance-text.income.training-position')],
                [Yii::t('console', 'migrations.finance-text.income.training-special')],
                [Yii::t('console', 'migrations.finance-text.income.training-power')],
                [Yii::t('console', 'migrations.finance-text.income.scout-style')],
                [Yii::t('console', 'migrations.finance-text.income.deal-check')],
                [Yii::t('console', 'migrations.finance-text.income.coach')],
                [Yii::t('console', 'migrations.finance-text.outcome.national')],
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
