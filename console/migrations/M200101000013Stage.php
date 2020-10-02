<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000013Stage
 * @package console\migrations
 */
class M200101000013Stage extends Migration
{
    private const TABLE = '{{%stage}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(25)->notNull(),
                'visitor' => $this->integer(3)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'visitor'],
            [
                ['-', 90],
                ['Round 1', 100],
                ['Round 2', 100],
                ['Round 3', 100],
                ['Round 4', 100],
                ['Round 5', 100],
                ['Round 6', 100],
                ['Round 7', 100],
                ['Round 8', 100],
                ['Round 9', 100],
                ['Round 10', 100],
                ['Round 11', 100],
                ['Round 12', 100],
                ['Round 13', 100],
                ['Round 14', 100],
                ['Round 15', 100],
                ['Round 16', 100],
                ['Round 17', 100],
                ['Round 18', 100],
                ['Round 19', 100],
                ['Round 20', 100],
                ['Round 21', 100],
                ['Round 22', 100],
                ['Round 23', 100],
                ['Round 24', 100],
                ['Round 25', 100],
                ['Round 26', 100],
                ['Round 27', 100],
                ['Round 28', 100],
                ['Round 29', 100],
                ['Round 30', 100],
                ['First qualifying round', 105],
                ['Second qualifying round', 105],
                ['Third qualifying round', 105],
                ['Round 1', 120],
                ['Round 2', 120],
                ['Round 3', 120],
                ['Round 4', 120],
                ['Round 5', 120],
                ['Round 6', 120],
                ['Round of 16', 170],
                ['Quarter-final', 180],
                ['Semi-final', 190],
                ['Final', 200],
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
