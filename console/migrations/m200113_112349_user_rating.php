<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_112349_user_rating
 * @package console\migrations
 */
class m200113_112349_user_rating extends Migration
{
    private const TABLE = '{{%user_rating}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_rating_id' => $this->primaryKey(11),
            'user_rating_auto' => $this->integer(11)->defaultValue(0),
            'user_rating_collision_loose' => $this->integer(11)->defaultValue(0),
            'user_rating_collision_win' => $this->integer(11)->defaultValue(0),
            'user_rating_game' => $this->integer(11)->defaultValue(0),
            'user_rating_loose' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_equal' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_overtime' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_overtime_equal' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_overtime_strong' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_overtime_weak' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_strong' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_super' => $this->integer(11)->defaultValue(0),
            'user_rating_loose_weak' => $this->integer(11)->defaultValue(0),
            'user_rating_rating' => $this->decimal(6.2)->defaultValue(0),
            'user_rating_season_id' => $this->integer(3)->defaultValue(0),
            'user_rating_user_id' => $this->integer(11)->defaultValue(0),
            'user_rating_vs_super' => $this->integer(11)->defaultValue(0),
            'user_rating_vs_rest' => $this->integer(11)->defaultValue(0),
            'user_rating_win' => $this->integer(11)->defaultValue(0),
            'user_rating_win_equal' => $this->integer(11)->defaultValue(0),
            'user_rating_win_overtime' => $this->integer(11)->defaultValue(0),
            'user_rating_win_overtime_equal' => $this->integer(11)->defaultValue(0),
            'user_rating_win_overtime_strong' => $this->integer(11)->defaultValue(0),
            'user_rating_win_overtime_weak' => $this->integer(11)->defaultValue(0),
            'user_rating_win_strong' => $this->integer(11)->defaultValue(0),
            'user_rating_win_super' => $this->integer(11)->defaultValue(0),
            'user_rating_win_weak' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('user_rating_season_id', self::TABLE, 'user_rating_season_id');
        $this->createIndex('user_rating_user_id', self::TABLE, 'user_rating_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
