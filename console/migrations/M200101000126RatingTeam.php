<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000126RatingTeam
 * @package console\migrations
 */
class M200101000126RatingTeam extends Migration
{
    private const TABLE = '{{%rating_team}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'age_place' => $this->integer(11)->defaultValue(0),
                'age_place_federation' => $this->integer(11)->defaultValue(0),
                'base_place' => $this->integer(11)->defaultValue(0),
                'base_place_federation' => $this->integer(11)->defaultValue(0),
                'finance_place' => $this->integer(11)->defaultValue(0),
                'finance_place_federation' => $this->integer(11)->defaultValue(0),
                'player_place' => $this->integer(11)->defaultValue(0),
                'player_place_federation' => $this->integer(11)->defaultValue(0),
                'power_vs_place' => $this->integer(11)->defaultValue(0),
                'power_vs_place_federation' => $this->integer(11)->defaultValue(0),
                'price_base_place' => $this->integer(11)->defaultValue(0),
                'price_base_place_federation' => $this->integer(11)->defaultValue(0),
                'price_stadium_place' => $this->integer(11)->defaultValue(0),
                'price_stadium_place_federation' => $this->integer(11)->defaultValue(0),
                'price_total_place' => $this->integer(11)->defaultValue(0),
                'price_total_place_federation' => $this->integer(11)->defaultValue(0),
                'salary_place' => $this->integer(11)->defaultValue(0),
                'salary_place_federation' => $this->integer(11)->defaultValue(0),
                'stadium_place' => $this->integer(11)->defaultValue(0),
                'stadium_place_federation' => $this->integer(11)->defaultValue(0),
                'team_id' => $this->integer(11)->defaultValue(0),
                'visitor_place' => $this->integer(11)->defaultValue(0),
                'visitor_place_federation' => $this->integer(11)->defaultValue(0),
            ]
        );

        $this->addForeignKey('rating_team_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
