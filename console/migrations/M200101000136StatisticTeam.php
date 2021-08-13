<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000136StatisticTeam
 * @package console\migrations
 */
class M200101000136StatisticTeam extends Migration
{
    private const TABLE = '{{%statistic_team}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'carry' => $this->integer(4)->defaultValue(0),
                'clean_break' => $this->integer(3)->defaultValue(0),
                'defender_beaten' => $this->integer(3)->defaultValue(0),
                'division_id' => $this->integer(1),
                'draw' => $this->integer(2)->defaultValue(0),
                'drop_goal' => $this->integer(2)->defaultValue(0),
                'federation_id' => $this->integer(3),
                'game' => $this->integer(2)->defaultValue(0),
                'loose' => $this->integer(2)->defaultValue(0),
                'metre_gained' => $this->integer(5)->defaultValue(0),
                'national_id' => $this->integer(5),
                'pass' => $this->integer(4)->defaultValue(0),
                'penalty_conceded' => $this->integer(3)->defaultValue(0),
                'point' => $this->integer(3)->defaultValue(0),
                'red_card' => $this->integer(2)->defaultValue(0),
                'season_id' => $this->integer(3),
                'tackle' => $this->integer(4)->defaultValue(0),
                'team_id' => $this->integer(11),
                'tournament_type_id' => $this->integer(1),
                'try' => $this->integer(2)->defaultValue(0),
                'turnover_won' => $this->integer(3)->defaultValue(0),
                'win' => $this->integer(2)->defaultValue(0),
                'yellow_card' => $this->integer(2)->defaultValue(0),
            ]
        );

        $this->addForeignKey('statistic_team_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('statistic_team_division_id', self::TABLE, 'division_id', '{{%division}}', 'id');
        $this->addForeignKey('statistic_team_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('statistic_team_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('statistic_team_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey(
            'statistic_team_tournament_type_id',
            self::TABLE,
            'tournament_type_id',
            '{{%tournament_type}}',
            'id'
        );

        $this->createIndex(
            'team_season_tournament',
            self::TABLE,
            ['team_id', 'national_id', 'season_id', 'tournament_type_id'],
            true
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
