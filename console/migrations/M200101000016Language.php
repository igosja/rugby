<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000016Language
 * @package console\migrations
 */
class M200101000016Language extends Migration
{
    private const TABLE = '{{%language}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'code' => $this->string(2)->notNull()->unique(),
                'name' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->insert(
            self::TABLE,
            [
                'code' => 'en',
                'name' => 'English',
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
