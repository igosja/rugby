<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000119Poll
 * @package console\migrations
 */
class M200101000119Poll extends Migration
{
    private const TABLE = '{{%poll}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'country_id' => $this->integer(3),
                'date' => $this->integer(11)->defaultValue(0),
                'poll_status_id' => $this->integer(1)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('poll_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey('poll_poll_status_id', self::TABLE, 'poll_status_id', '{{%poll_status}}', 'id');
        $this->addForeignKey('poll_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
