<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210820131821TranslateVote
 * @package console\migrations
 */
class M210820131821TranslateVote extends Migration
{
    private const TABLE = '{{%translate_vote}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'date' => $this->integer(),
            'translate_option_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->addForeignKey('translate_vote_translate_option_id', self::TABLE, 'translate_option_id', '{{%translate_option}}', 'id');
        $this->addForeignKey('translate_vote_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
