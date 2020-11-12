<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000101LineupSpecial
 * @package console\migrations
 */
class M200101000101LineupSpecial extends Migration
{
    private const TABLE = '{{%lineup_special}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'level' => $this->integer(1)->defaultValue(0),
                'lineup_id' => $this->integer(11)->defaultValue(0),
                'special_id' => $this->integer(2)->defaultValue(0),
            ]
        );

        $this->addForeignKey('lineup_special_lineup_id', self::TABLE, 'lineup_id', '{{%lineup}}', 'id');
        $this->addForeignKey('lineup_special_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');

        $this->createIndex('lineup_special', self::TABLE, ['lineup_id', 'special_id'], true);

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
