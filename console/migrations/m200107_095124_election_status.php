<?php

use yii\db\Migration;

/**
 * Class m200107_095124_election_status
 */
class m200107_095124_election_status extends Migration
{
    const TABLE = '{{%election_status}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'election_status_id' => $this->primaryKey(1),
            'election_status_name' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['election_status_name'], [
            ['Сбор кандидатур'],
            ['Идет голосование'],
            ['Закрыто'],
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
