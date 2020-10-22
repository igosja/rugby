<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000040BuildingBase
 * @package console\migrations
 */
class M200101000040BuildingBase extends Migration
{
    private const TABLE = '{{%building_base}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'building_id' => $this->integer(1)->notNull(),
                'construction_type_id' => $this->integer(1)->notNull(),
                'date' => $this->integer(11)->notNull(),
                'day' => $this->integer(2)->notNull(),
                'ready' => $this->integer(11),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('building_base_building_id', self::TABLE, 'building_id', '{{%building}}', 'id');
        $this->addForeignKey(
            'building_base_construction_type_id',
            self::TABLE,
            'construction_type_id',
            '{{%construction_type}}',
            'id'
        );
        $this->addForeignKey('building_base_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('building_base_ready', self::TABLE, 'ready');

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
