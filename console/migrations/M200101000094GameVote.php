<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000094GameVote
 * @package console\migrations
 */
class M200101000094GameVote extends Migration
{
    private const TABLE = '{{%game_vote}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'game_id' => $this->integer(11)->notNull(),
                'rating' => $this->integer(1)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('game_vote_game_id', self::TABLE, 'game_id', '{{%game}}', 'id');
        $this->addForeignKey('game_vote_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('game_user', self::TABLE, ['game_id', 'user_id'], true);

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
