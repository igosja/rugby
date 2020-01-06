<?php

use yii\db\Migration;

/**
 * Class m200107_095344_event_type
 */
class m200107_095344_event_type extends Migration
{
    const TABLE = '{{%event_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'event_type_id' => $this->primaryKey(1),
            'event_type_text' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['event_type_text'], [
            ['Score'],
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
