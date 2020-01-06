<?php

namespace frontend\models\queries;

use common\models\db\Player;
use yii\db\ActiveQuery;

/**
 * Class PlayerQuery
 * @package frontend\models\queries
 */
class PlayerQuery
{
    /**
     * @param int $playerId
     * @return Player|null
     */
    public static function getPlayerById(int $playerId): ?Player
    {
        /**
         * @var Player $result
         */
        $result = Player::find()
            ->select([
                '*',
            ])
            ->where(['player_id' => $playerId])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getPlayerTeamList(int $teamId): ActiveQuery
    {
        return Player::find()
            ->with([
                'country' => static function (ActiveQuery $query) {
                    $query->select([
                        'country_id',
                        'country_name',
                    ]);
                },
                'loan' => static function (ActiveQuery $query) {
                    $query->select([
                        'loan_player_id',
                    ]);
                },
                'name' => static function (ActiveQuery $query) {
                    $query->select([
                        'name_id',
                        'name_name',
                    ]);
                },
                'national' => static function (ActiveQuery $query) {
                    $query
                        ->select([
                            'national_id',
                        ]);
                },
                'physical' => static function (ActiveQuery $query) {
                    $query->select([
                        'physical_id',
                        'physical_name',
                    ]);
                },
                'playerPositions' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'position' => static function (ActiveQuery $query) {
                                $query->select([
                                    'position_id',
                                    'position_name',
                                    'position_text',
                                ]);
                            },
                        ])
                        ->select([
                            'player_position_player_id',
                            'player_position_position_id',
                        ]);
                },
                'playerSpecials' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'special' => static function (ActiveQuery $query) {
                                $query->select([
                                    'special_id',
                                    'special_name',
                                    'special_text',
                                ]);
                            },
                        ])
                        ->select([
                            'player_special_level',
                            'player_special_player_id',
                            'player_special_special_id',
                        ]);
                },
                'scout' => static function (ActiveQuery $query) {
                    $query->select([
                        'scout_id',
                        'scout_player_id',
                    ]);
                },
                'scouts' => static function (ActiveQuery $query) {
                    $query->select([
                        'scout_id',
                        'scout_player_id',
                    ]);
                },
                'squad' => static function (ActiveQuery $query) {
                    $query->select([
                        'squad_id',
                        'squad_color',
                    ]);
                },
                'statisticPlayer' => static function (ActiveQuery $query) {
                    $query->select([
                        'statistic_player_game',
                        'statistic_player_player_id',
                    ]);
                },
                'surname' => static function (ActiveQuery $query) {
                    $query->select([
                        'surname_id',
                        'surname_name',
                    ]);
                },
                'training' => static function (ActiveQuery $query) {
                    $query->select([
                        'training_player_id',
                    ]);
                },
                'transfer' => static function (ActiveQuery $query) {
                    $query->select([
                        'transfer_player_id',
                    ]);
                },
            ])
            ->select([
                'player_age',
                'player_country_id',
                'player_game_row',
                'player_injury',
                'player_injury_day',
                'player_id',
                'player_loan_day',
                'player_name_id',
                'player_national_id',
                'player_physical_id',
                'player_power_nominal',
                'player_power_old',
                'player_power_real',
                'player_price',
                'player_squad_id',
                'player_surname_id',
                'player_team_id',
                'player_tire',
            ])
            ->where([
                'or',
                ['player_team_id' => $teamId],
                ['player_loan_team_id' => $teamId]
            ]);
    }
}
