<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210820131711TranslateOption
 * @package console\migrations
 */
class M210820131711TranslateOption extends Migration
{
    private const TABLE = '{{%translate_option}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'date' => $this->integer(),
            'text' => $this->text(),
            'translate_key_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->addForeignKey('translate_option_translate_key_id', self::TABLE, 'translate_key_id', '{{%translate_key}}', 'id');
        $this->addForeignKey('translate_option_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
