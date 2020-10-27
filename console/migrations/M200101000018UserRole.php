<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000018UserRole
 * @package console\migrations
 */
class M200101000018UserRole extends Migration
{
    private const TABLE = '{{%user_role}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(20)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['User'],
                ['Support'],
                ['Editor'],
                ['Moderator'],
                ['Administrator'],
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
