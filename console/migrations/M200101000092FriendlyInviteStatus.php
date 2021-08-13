<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000092FriendlyInviteStatus
 * @package console\migrations
 */
class M200101000092FriendlyInviteStatus extends Migration
{
    private const TABLE = '{{%friendly_invite_status}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                [Yii::t('console', 'migrations.friendly-invite-status.new')],
                [Yii::t('console', 'migrations.friendly-invite-status.accepted')],
                [Yii::t('console', 'migrations.friendly-invite-status.canceled')],
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
