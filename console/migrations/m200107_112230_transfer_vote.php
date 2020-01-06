<?php

use yii\db\Migration;

/**
 * Class m200107_112230_transfer_vote
 */
class m200107_112230_transfer_vote extends Migration
{
    const TABLE = '{{%transfer_vote}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'transfer_vote_id' => $this->primaryKey(11),
            'transfer_vote_transfer_id' => $this->integer(11)->defaultValue(0),
            'transfer_vote_rating' => $this->integer(2)->defaultValue(0),
            'transfer_vote_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('transfer_vote_transfer_id', self::TABLE, 'transfer_vote_transfer_id');
        $this->createIndex('transfer_vote_user_id', self::TABLE, 'transfer_vote_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
