<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000116PlayerPosition
 * @package console\migrations
 */
class M200101000116PlayerPosition extends Migration
{
    private const TABLE = '{{%player_position}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'player_id' => $this->integer(11)->notNull(),
                'position_id' => $this->integer(2)->notNull(),
            ]
        );

        $this->addForeignKey('player_position_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('player_position_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');

        $this->createIndex('player_position', self::TABLE, ['player_id', 'position_id'], true);

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
