<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000001Season
 * @package console\migrations
 */
class M200101000001Season extends Migration
{
    private const TABLE = '{{%season}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(3),
            ]
        );

        $this->insert(
            self::TABLE,
            [
                'id' => null,
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
