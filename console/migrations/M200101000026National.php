<?php

// TODO refactor

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
                'federation_id' => $this->integer(3)->notNull(),
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
                'stadium_id' => $this->integer(11)->defaultValue(0),
                'user_id' => $this->integer(11)->defaultValue(0),
                'vice_user_id' => $this->integer(11),
                'visitor' => $this->integer(3)->defaultValue(0),
            ]
        );

        $this->addForeignKey('national_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('national_national_type_id', self::TABLE, 'national_type_id', '{{%national_type}}', 'id');
        $this->addForeignKey('national_stadium_id', self::TABLE, 'stadium_id', '{{%stadium}}', 'id');
        $this->addForeignKey('national_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('national_vice_user_id', self::TABLE, 'vice_user_id', '{{%user}}', 'id');

        $this->batchInsert(
            self::TABLE,
            ['federation_id', 'national_type_id'],
            [
                [7, 1],
                [9, 1],
                [54, 1],
                [61, 1],
                [81, 1],
                [83, 1],
                [124, 1],
                [154, 1],
                [164, 1],
                [194, 1],
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
