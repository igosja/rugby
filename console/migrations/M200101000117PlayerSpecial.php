<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000117PlayerSpecial
 * @package console\migrations
 */
class M200101000117PlayerSpecial extends Migration
{
    private const TABLE = '{{%player_special}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'level' => $this->integer(1)->notNull(),
                'player_id' => $this->integer(11)->notNull(),
                'special_id' => $this->integer(2)->notNull(),
            ]
        );

        $this->addForeignKey('player_special_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('player_special_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');

        $this->createIndex('player_special', self::TABLE, ['player_id', 'special_id'], true);

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
