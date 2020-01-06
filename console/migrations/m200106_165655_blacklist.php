<?php

use yii\db\Migration;

/**
 * Class m200106_165655_blacklist
 */
class m200106_165655_blacklist extends Migration
{
    const TABLE = '{{%blacklist}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'blacklist_id' => $this->primaryKey(),
            'blacklist_interlocutor_user_id' => $this->integer()->defaultValue(0),
            'blacklist_owner_user_id' => $this->integer()->defaultValue(0),
        ]);

        $this->createIndex('blacklist_interlocutor_user_id', self::TABLE, 'blacklist_interlocutor_user_id');
        $this->createIndex('blacklist_owner_user_id', self::TABLE, 'blacklist_owner_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
