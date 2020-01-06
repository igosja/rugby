<?php

use yii\db\Migration;

/**
 * Class m200107_102351_game_vote
 */
class m200107_102351_game_vote extends Migration
{
    const TABLE = '{{%game_vote}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'game_vote_id' => $this->primaryKey(11),
            'game_vote_game_id' => $this->integer(11)->defaultValue(0),
            'game_vote_rating' => $this->integer(2)->defaultValue(0),
            'game_vote_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('game_vote_game_id', self::TABLE, 'game_vote_game_id');
        $this->createIndex('game_vote_user_id', self::TABLE, 'game_vote_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
