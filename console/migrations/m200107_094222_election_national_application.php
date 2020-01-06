<?php

use yii\db\Migration;

/**
 * Class m200107_094222_election_national_application
 */
class m200107_094222_election_national_application extends Migration
{
    const TABLE = '{{%election_national_application}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_national_application_id' => $this->primaryKey(11),
            'election_national_application_date' => $this->integer(11)->defaultValue(0),
            'election_national_application_election_id' => $this->integer(11)->defaultValue(0),
            'election_national_application_text' => $this->text(),
            'election_national_application_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('election_national_application_election_id', self::TABLE, 'election_national_application_election_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
