<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000093FriendlyInvite
 * @package console\migrations
 */
class M200101000093FriendlyInvite extends Migration
{
    private const TABLE = '{{%friendly_invite}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->notNull(),
                'friendly_invite_status_id' => $this->integer(1)->notNull(),
                'guest_team_id' => $this->integer(11)->notNull(),
                'guest_user_id' => $this->integer(11)->notNull(),
                'home_team_id' => $this->integer(11)->notNull(),
                'home_user_id' => $this->integer(11)->notNull(),
                'schedule_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'friendly_invite_friendly_invite_status_id',
            self::TABLE,
            'friendly_invite_status_id',
            '{{%friendly_invite_status}}',
            'id'
        );
        $this->addForeignKey('friendly_invite_guest_team_id', self::TABLE, 'guest_team_id', '{{%team}}', 'id');
        $this->addForeignKey('friendly_invite_guest_user_id', self::TABLE, 'guest_user_id', '{{%user}}', 'id');
        $this->addForeignKey('friendly_invite_home_team_id', self::TABLE, 'home_team_id', '{{%team}}', 'id');
        $this->addForeignKey('friendly_invite_home_user_id', self::TABLE, 'home_user_id', '{{%user}}', 'id');
        $this->addForeignKey('friendly_invite_schedule_id', self::TABLE, 'schedule_id', '{{%schedule}}', 'id');

        $this->createIndex('schedule_team', self::TABLE, ['schedule_id', 'home_team_id', 'guest_team_id'], true);

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
