<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_094543_election_national_vote
 * @package console\migrations
 */
class m200107_094543_election_national_vote extends Migration
{
    private const TABLE = '{{%election_national_vote}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_national_vote_id' => $this->primaryKey(11),
            'election_national_vote_application_id' => $this->integer(11)->defaultValue(0),
            'election_national_vote_date' => $this->integer(11)->defaultValue(0),
            'election_national_vote_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('election_national_vote_application_id', self::TABLE, 'election_national_vote_application_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
