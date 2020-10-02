<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000110NationalUserDay
 * @package console\migrations
 */
class M200101000110NationalUserDay extends Migration
{
    private const TABLE = '{{%national_user_day}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'day' => $this->integer(3)->notNull(),
                'national_id' => $this->integer(3)->notNull(),
                'season_id' => $this->integer(3)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('national_user_day_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('national_user_day_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('national_user_day_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('national_user_season', self::TABLE, ['national_id', 'user_id', 'season_id'], true);

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
