<?php

use yii\db\Migration;

/**
 * Class m200107_095055_election_president_vote
 */
class m200107_095055_election_president_vote extends Migration
{
    const TABLE = '{{%election_president_vote}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_president_vote_id' => $this->primaryKey(11),
            'election_president_vote_application_id' => $this->integer(11)->defaultValue(0),
            'election_president_vote_date' => $this->integer(11)->defaultValue(0),
            'election_president_vote_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('election_president_vote_application_id', self::TABLE, 'election_president_vote_application_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
