<?php

use yii\db\Migration;

/**
 * Class m200107_103223_message
 */
class m200107_103223_message extends Migration
{
    const TABLE = '{{%message}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'message_id' => $this->primaryKey(11),
            'message_date' => $this->integer(11)->defaultValue(0),
            'message_read' => $this->integer(11)->defaultValue(0),
            'message_text' => $this->text(),
            'message_from_user_id' => $this->integer(11)->defaultValue(0),
            'message_to_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('message_read', self::TABLE, 'message_read');
        $this->createIndex('message_from_user_id', self::TABLE, 'message_from_user_id');
        $this->createIndex('message_to_user_id', self::TABLE, 'message_to_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
