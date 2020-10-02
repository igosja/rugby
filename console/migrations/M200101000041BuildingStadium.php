<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000041BuildingStadium
 * @package console\migrations
 */
class M200101000041BuildingStadium extends Migration
{
    private const TABLE = '{{%building_stadium}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'capacity' => $this->integer(5)->notNull(),
                'construction_type_id' => $this->integer(1)->notNull(),
                'date' => $this->integer(11)->defaultValue(0),
                'day' => $this->integer(2)->defaultValue(0),
                'ready' => $this->integer(11)->defaultValue(0),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'building_stadium_construction_type_id',
            self::TABLE,
            'construction_type_id',
            '{{%construction_type}}',
            'id'
        );
        $this->addForeignKey('building_stadium_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('building_stadium_ready', self::TABLE, 'ready');

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
