<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000146UserHoliday
 * @package console\migrations
 */
class M200101000146UserHoliday extends Migration
{
    private const TABLE = '{{%user_holiday}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date_end' => $this->integer(11),
                'date_start' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('user_holiday_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
