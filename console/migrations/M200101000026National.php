<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000026National
 * @package console\migrations
 */
class M200101000026National extends Migration
{
    private const TABLE = '{{%national}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(3),
                'country_id' => $this->integer(3)->notNull(),
                'finance' => $this->integer(11)->defaultValue(0),
                'mood_rest' => $this->integer(1)->defaultValue(0),
                'mood_super' => $this->integer(1)->defaultValue(0),
                'national_type_id' => $this->integer(1)->notNull(),
                'power_c_15' => $this->integer(5)->defaultValue(0),
                'power_c_19' => $this->integer(5)->defaultValue(0),
                'power_c_24' => $this->integer(5)->defaultValue(0),
                'power_s_15' => $this->integer(5)->defaultValue(0),
                'power_s_19' => $this->integer(5)->defaultValue(0),
                'power_s_24' => $this->integer(5)->defaultValue(0),
                'power_v' => $this->integer(5)->defaultValue(0),
                'power_vs' => $this->integer(5)->defaultValue(0),
                'stadium_id' => $this->integer(11),
                'user_id' => $this->integer(11),
                'vice_user_id' => $this->integer(11),
                'visitor' => $this->integer(3)->defaultValue(0),
            ]
        );

        $this->addForeignKey('national_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey('national_national_type_id', self::TABLE, 'national_type_id', '{{%national_type}}', 'id');
        $this->addForeignKey('national_stadium_id', self::TABLE, 'stadium_id', '{{%stadium}}', 'id');
        $this->addForeignKey('national_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('national_vice_user_id', self::TABLE, 'vice_user_id', '{{%user}}', 'id');

        $this->batchInsert(
            self::TABLE,
            ['country_id', 'national_type_id'],
            [
                [7, 1],
                [7, 2],
                [7, 3],
                [9, 1],
                [9, 2],
                [9, 3],
                [54, 1],
                [54, 2],
                [54, 3],
                [61, 1],
                [61, 2],
                [61, 3],
                [81, 1],
                [81, 2],
                [81, 3],
                [83, 1],
                [83, 2],
                [83, 3],
                [124, 1],
                [124, 2],
                [124, 3],
                [154, 1],
                [154, 2],
                [154, 3],
                [164, 1],
                [164, 2],
                [164, 3],
                [194, 1],
                [194, 2],
                [194, 3],
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
