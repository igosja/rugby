<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000135StatisticPlayer
 * @package console\migrations
 */
class M200101000135StatisticPlayer extends Migration
{
    private const TABLE = '{{%statistic_player}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'assist' => $this->integer(3)->defaultValue(0),
                'assist_power' => $this->integer(3)->defaultValue(0),
                'assist_short' => $this->integer(3)->defaultValue(0),
                'shootout_win' => $this->integer(2)->defaultValue(0),
                'federation_id' => $this->integer(3),
                'division_id' => $this->integer(1),
                'face_off' => $this->integer(3)->defaultValue(0),
                'face_off_percent' => $this->decimal(5, 2)->defaultValue(0),
                'face_off_win' => $this->integer(3)->defaultValue(0),
                'game' => $this->integer(2)->defaultValue(0),
                'game_with_shootout' => $this->integer(2)->defaultValue(0),
                'loose' => $this->integer(2)->defaultValue(0),
                'national_id' => $this->integer(5),
                'pass' => $this->integer(3)->defaultValue(0),
                'pass_per_game' => $this->decimal(4, 2)->defaultValue(0),
                'penalty' => $this->integer(3)->defaultValue(0),
                'player_id' => $this->integer(11)->notNull(),
                'plus_minus' => $this->integer(3)->defaultValue(0),
                'point' => $this->integer(3)->defaultValue(0),
                'save' => $this->integer(3)->defaultValue(0),
                'save_percent' => $this->decimal(5, 2)->defaultValue(0),
                'score' => $this->integer(3)->defaultValue(0),
                'score_draw' => $this->integer(3)->defaultValue(0),
                'score_power' => $this->integer(3)->defaultValue(0),
                'score_short' => $this->integer(3)->defaultValue(0),
                'score_shot_percent' => $this->decimal(5, 2)->defaultValue(0),
                'score_win' => $this->integer(2)->defaultValue(0),
                'season_id' => $this->integer(3),
                'shot' => $this->integer(3)->defaultValue(0),
                'shot_gk' => $this->integer(3)->defaultValue(0),
                'shot_per_game' => $this->decimal(4, 2)->defaultValue(0),
                'shutout' => $this->integer(3)->defaultValue(0),
                'team_id' => $this->integer(11),
                'tournament_type_id' => $this->integer(1),
                'win' => $this->integer(2)->defaultValue(0),
            ]
        );

        $this->addForeignKey('statistic_player_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('statistic_player_division_id', self::TABLE, 'division_id', '{{%division}}', 'id');
        $this->addForeignKey('statistic_player_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('statistic_player_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('statistic_player_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('statistic_player_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey(
            'statistic_player_tournament_type_id',
            self::TABLE,
            'tournament_type_id',
            '{{%tournament_type}}',
            'id'
        );

        $this->createIndex(
            'player_team_season_tournament',
            self::TABLE,
            ['player_id', 'team_id', 'national_id', 'season_id', 'tournament_type_id'],
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
