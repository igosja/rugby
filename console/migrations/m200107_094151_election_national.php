<?php

use yii\db\Migration;

/**
 * Class m200107_094151_election_national
 */
class m200107_094151_election_national extends Migration
{
    const TABLE = '{{%election_national}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_national_id' => $this->primaryKey(11),
            'election_national_country_id' => $this->integer(3)->defaultValue(0),
            'election_national_date' => $this->integer(11)->defaultValue(0),
            'election_national_election_status_id' => $this->integer(1)->defaultValue(0),
            'election_national_national_type_id' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('election_national_country_id', self::TABLE, 'election_national_country_id');
        $this->createIndex('election_national_election_status_id', self::TABLE, 'election_national_election_status_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
