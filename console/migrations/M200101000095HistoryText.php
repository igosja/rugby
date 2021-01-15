<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000095HistoryText
 * @package console\migrations
 */
class M200101000095HistoryText extends Migration
{
    private const TABLE = '{{%history_text}}';

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
                [Yii::t('console', 'migrations.history-text.team.register')],
                [Yii::t('console', 'migrations.history-text.team.re-register')],
                [Yii::t('console', 'migrations.history-text.user.manager-team-in')],
                [Yii::t('console', 'migrations.history-text.user.manager-team-out')],
                [Yii::t('console', 'migrations.history-text.user.vice-team-in')],
                [Yii::t('console', 'migrations.history-text.user.vice-team-out')],
                [Yii::t('console', 'migrations.history-text.user.manager-national-in')],
                [Yii::t('console', 'migrations.history-text.user.manager-national-out')],
                [Yii::t('console', 'migrations.history-text.user.vice-national-in')],
                [Yii::t('console', 'migrations.history-text.user.vice-national-out')],
                [Yii::t('console', 'migrations.history-text.user.president-in')],
                [Yii::t('console', 'migrations.history-text.user.president-out')],
                [Yii::t('console', 'migrations.history-text.user.vice-president-in')],
                [Yii::t('console', 'migrations.history-text.user.vice-president-out')],
                [Yii::t('console', 'migrations.history-text.building.up')],
                [Yii::t('console', 'migrations.history-text.building.down')],
                [Yii::t('console', 'migrations.history-text.stadium.up')],
                [Yii::t('console', 'migrations.history-text.stadium.down')],
                [Yii::t('console', 'migrations.history-text.change-style')],
                [Yii::t('console', 'migrations.history-text.change-special')],
                [Yii::t('console', 'migrations.history-text.vip.1-place')],
                [Yii::t('console', 'migrations.history-text.vip.2-place')],
                [Yii::t('console', 'migrations.history-text.vip.3-place')],
                [Yii::t('console', 'migrations.history-text.player.from-school')],
                [Yii::t('console', 'migrations.history-text.player.pension-say')],
                [Yii::t('console', 'migrations.history-text.player.pension-go')],
                [Yii::t('console', 'migrations.history-text.player.training-point')],
                [Yii::t('console', 'migrations.history-text.player.training-position')],
                [Yii::t('console', 'migrations.history-text.player.training-special')],
                [Yii::t('console', 'migrations.history-text.player.game-point-plus')],
                [Yii::t('console', 'migrations.history-text.player.game-point-minus')],
                [Yii::t('console', 'migrations.history-text.player.championship-special')],
                [Yii::t('console', 'migrations.history-text.player.bonus-point')],
                [Yii::t('console', 'migrations.history-text.player.bonus-position')],
                [Yii::t('console', 'migrations.history-text.player.bonus-special')],
                [Yii::t('console', 'migrations.history-text.player.injury')],
                [Yii::t('console', 'migrations.history-text.player.transfer')],
                [Yii::t('console', 'migrations.history-text.player.exchange')],
                [Yii::t('console', 'migrations.history-text.player.loan')],
                [Yii::t('console', 'migrations.history-text.player.loan-back')],
                [Yii::t('console', 'migrations.history-text.player.free')],
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
