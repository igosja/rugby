<?php

use yii\db\Migration;

/**
 * Class m200107_105322_rating_user
 */
class m200107_105322_rating_user extends Migration
{
    const TABLE = '{{%rating_user}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'rating_user_id' => $this->primaryKey(11),
            'rating_user_rating_place' => $this->integer(11)->defaultValue(0),
            'rating_user_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('rating_user_user_id', self::TABLE, 'rating_user_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
