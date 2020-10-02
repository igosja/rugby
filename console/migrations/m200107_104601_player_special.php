<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104601_player_special
 * @package console\migrations
 */
class m200107_104601_player_special extends Migration
{
    private const TABLE = '{{%player_special}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'player_special_id' => $this->primaryKey(),
            'player_special_level' => $this->integer(1)->defaultValue(0),
            'player_special_player_id' => $this->integer(11)->defaultValue(0),
            'player_special_special_id' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('player_special_player_id', self::TABLE, 'player_special_player_id');
        $this->createIndex('player_special_special_id', self::TABLE, 'player_special_special_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
