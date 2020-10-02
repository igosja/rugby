<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_102336_game_comment
 * @package console\migrations
 */
class m200107_102336_game_comment extends Migration
{
    private const TABLE = '{{%game_comment}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'game_comment_id' => $this->primaryKey(11),
            'game_comment_check' => $this->integer(11)->defaultValue(0),
            'game_comment_date' => $this->integer(11)->defaultValue(0),
            'game_comment_game_id' => $this->integer(1)->defaultValue(0),
            'game_comment_text' => $this->text(),
            'game_comment_user_id' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('game_comment_check', self::TABLE, 'game_comment_check');
        $this->createIndex('game_comment_game_id', self::TABLE, 'game_comment_game_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
