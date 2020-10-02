<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111606_team_visitor
 * @package console\migrations
 */
class m200107_111606_team_visitor extends Migration
{
    private const TABLE = '{{%team_visitor}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'team_visitor_id' => $this->primaryKey(1),
            'team_visitor_team_id' => $this->integer(11)->defaultValue(0),
            'team_visitor_visitor' => $this->decimal(3, 2)->defaultValue(0),
        ]);

        $this->createIndex('team_visitor_team_id', self::TABLE, 'team_visitor_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
