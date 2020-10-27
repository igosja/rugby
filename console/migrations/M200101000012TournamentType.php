<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000012TournamentType
 * @package console\migrations
 */
class M200101000012TournamentType extends Migration
{
    private const TABLE = '{{%tournament_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'day_type_id' => $this->integer(1)->notNull(),
                'name' => $this->string(20)->notNull()->unique(),
                'visitor' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('tournament_type_day_type_id', self::TABLE, 'day_type_id', '{{%day_type}}', 'id');

        $this->batchInsert(
            self::TABLE,
            ['day_type_id', 'name', 'visitor'],
            [
                [3, 'Чемпионат мира', 200],
                [3, 'Лига Чемпионов', 150],
                [2, 'Чемпионат', 100],
                [2, 'Конференция', 90],
                [2, 'Кубок межсезонья', 90],
                [1, 'Товарищеский матч', 80],
            ]
        );

        return true;
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
