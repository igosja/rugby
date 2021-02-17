<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000054DealReason
 * @package console\migrations
 */
class M200101000054DealReason extends Migration
{
    private const TABLE = '{{%deal_reason}}';

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
                [Yii::t('console', 'migrations.deal-reason.manager-limit')],
                [Yii::t('console', 'migrations.deal-reason.team-limit')],
                [Yii::t('console', 'migrations.deal-reason.no-money')],
                [Yii::t('console', 'migrations.deal-reason.not-best')],
                [Yii::t('console', 'migrations.deal-reason.referrer')],
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
