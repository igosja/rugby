<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000140TeamRequest
 * @package console\migrations
 */
class M200101000140TeamRequest extends Migration
{
    private const TABLE = '{{%team_request}}';

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
                'leave_team_id' => $this->integer(11),
                'team_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('team_request_leave_team_id', self::TABLE, 'leave_team_id', '{{%team}}', 'id');
        $this->addForeignKey('team_request_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('team_request_user_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('team_user', self::TABLE, ['team_id', 'user_id'], true);

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
