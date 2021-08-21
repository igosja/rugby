<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210820131540TranslateKey
 * @package console\migrations
 */
class M210820131540TranslateKey extends Migration
{
    private const TABLE = '{{%translate_key}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->string(),
            'text' => $this->text(),
        ]);

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
