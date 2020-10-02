<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_102613_language
 * @package console\migrations
 */
class m200107_102613_language extends Migration
{
    private const TABLE = '{{%language}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'language_id' => $this->primaryKey(11),
            'language_code' => $this->string(2),
            'language_name' => $this->string(255),
        ]);

        $this->insert(self::TABLE, [
            'language_code' => 'en',
            'language_name' => 'English',
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
