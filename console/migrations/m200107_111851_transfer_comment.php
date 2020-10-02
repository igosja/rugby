<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111851_transfer_comment
 * @package console\migrations
 */
class m200107_111851_transfer_comment extends Migration
{
    private const TABLE = '{{%transfer_comment}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'transfer_comment_id' => $this->primaryKey(11),
            'transfer_comment_check' => $this->integer(1)->defaultValue(0),
            'transfer_comment_date' => $this->integer(11)->defaultValue(0),
            'transfer_comment_transfer_id' => $this->integer(11)->defaultValue(0),
            'transfer_comment_text' => $this->text(),
            'transfer_comment_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('transfer_comment_check', self::TABLE, 'transfer_comment_check');
        $this->createIndex('transfer_comment_transfer_id', self::TABLE, 'transfer_comment_transfer_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
