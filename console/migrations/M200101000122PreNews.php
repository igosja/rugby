<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000122PreNews
 * @package console\migrations
 */
class M200101000122PreNews extends Migration
{
    private const TABLE = '{{%pre_news}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'new' => $this->text(),
                'error' => $this->text(),
            ]
        );

        $this->insert(
            self::TABLE,
            [
                'id' => null
            ]
        );

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
