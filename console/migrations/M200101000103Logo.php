<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000103Logo
 * @package console\migrations
 */
class M200101000103Logo extends Migration
{
    private const TABLE = '{{%logo}}';

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
                'team_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('logo_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('logo_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
