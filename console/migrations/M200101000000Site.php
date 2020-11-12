<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000000Site
 * @package console\migrations
 */
class M200101000000Site extends Migration
{
    private const TABLE = '{{%site}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'date_cron' => $this->integer(11),
                'status' => $this->boolean()->defaultValue(true),
                'version_1' => $this->integer(3)->defaultValue(0),
                'version_2' => $this->integer(3)->defaultValue(0),
                'version_3' => $this->integer(3)->defaultValue(0),
                'version_date' => $this->integer(11)->defaultValue(0),
            ]
        );

        $this->insert(
            self::TABLE,
            [
                'version_3' => 1,
                'version_date' => time(),
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
