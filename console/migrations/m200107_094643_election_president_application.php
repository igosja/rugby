<?php

use yii\db\Migration;

/**
 * Class m200107_094643_election_president_application
 */
class m200107_094643_election_president_application extends Migration
{
    const TABLE = '{{%election_president_application}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_president_application_id' => $this->primaryKey(11),
            'election_president_application_date' => $this->integer(11)->defaultValue(0),
            'election_president_application_election_id' => $this->integer(11)->defaultValue(0),
            'election_president_application_text' => $this->text(),
            'election_president_application_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('election_president_application_election_id', self::TABLE, 'election_president_application_election_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
