<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000148UserRating
 * @package console\migrations
 */
class M200101000148UserRating extends Migration
{
    private const TABLE = '{{%user_rating}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'auto' => $this->integer(11)->defaultValue(0),
                'collision_loose' => $this->integer(11)->defaultValue(0),
                'collision_win' => $this->integer(11)->defaultValue(0),
                'game' => $this->integer(11)->defaultValue(0),
                'loose' => $this->integer(11)->defaultValue(0),
                'loose_equal' => $this->integer(11)->defaultValue(0),
                'loose_overtime' => $this->integer(11)->defaultValue(0),
                'loose_overtime_equal' => $this->integer(11)->defaultValue(0),
                'loose_overtime_strong' => $this->integer(11)->defaultValue(0),
                'loose_overtime_weak' => $this->integer(11)->defaultValue(0),
                'loose_strong' => $this->integer(11)->defaultValue(0),
                'loose_super' => $this->integer(11)->defaultValue(0),
                'loose_weak' => $this->integer(11)->defaultValue(0),
                'rating' => $this->decimal(6.2)->defaultValue(0),
                'season_id' => $this->integer(3),
                'user_id' => $this->integer(11)->notNull(),
                'vs_super' => $this->integer(11)->defaultValue(0),
                'vs_rest' => $this->integer(11)->defaultValue(0),
                'win' => $this->integer(11)->defaultValue(0),
                'win_equal' => $this->integer(11)->defaultValue(0),
                'win_overtime' => $this->integer(11)->defaultValue(0),
                'win_overtime_equal' => $this->integer(11)->defaultValue(0),
                'win_overtime_strong' => $this->integer(11)->defaultValue(0),
                'win_overtime_weak' => $this->integer(11)->defaultValue(0),
                'win_strong' => $this->integer(11)->defaultValue(0),
                'win_super' => $this->integer(11)->defaultValue(0),
                'win_weak' => $this->integer(11)->defaultValue(0),
            ]
        );

        $this->addForeignKey('user_rating_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('user_rating_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('user_season', self::TABLE, ['user_id', 'season_id'], true);

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
