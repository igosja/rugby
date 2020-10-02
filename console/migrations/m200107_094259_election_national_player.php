<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_094259_election_national_player
 * @package console\migrations
 */
class m200107_094259_election_national_player extends Migration
{
    private const TABLE = '{{%election_national_player}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_national_player_id' => $this->primaryKey(11),
            'election_national_player_application_id' => $this->integer(11)->defaultValue(0),
            'election_national_player_player_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('election_national_player_application_id', self::TABLE, 'election_national_player_application_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
