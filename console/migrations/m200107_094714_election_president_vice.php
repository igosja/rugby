<?php

use yii\db\Migration;

/**
 * Class m200107_094714_election_president_vice
 */
class m200107_094714_election_president_vice extends Migration
{
    const TABLE = '{{%election_president_vice}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_president_vice_id' => $this->primaryKey(11),
            'election_president_vice_country_id' => $this->integer(3)->defaultValue(0),
            'election_president_vice_date' => $this->integer(11)->defaultValue(0),
            'election_president_vice_election_status_id' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('election_president_vice_country_id', self::TABLE, 'election_president_vice_country_id');
        $this->createIndex('election_president_vice_election_status_id', self::TABLE, 'election_president_vice_election_status_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
