<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000053GameComment
 * @package console\migrations
 */
class M200101000053GameComment extends Migration
{
    private const TABLE = '{{%game_comment}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'check' => $this->integer(11),
                'date' => $this->integer(11)->notNull(),
                'game_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('game_comment_game_id', self::TABLE, 'game_id', '{{%game}}', 'id');
        $this->addForeignKey('game_comment_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('check', self::TABLE, 'check');

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
