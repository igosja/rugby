<?php

namespace frontend\models\preparers;

use common\models\db\Team;
use frontend\models\queries\PlayerQuery;
use yii\data\ActiveDataProvider;

/**
 * Class PlayerPrepare
 * @package frontend\models\preparers
 */
class PlayerPrepare
{
    /**
     * @param Team $team
     * @return ActiveDataProvider
     */
    public static function getPlayerTeamDataProvider(Team $team): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => PlayerQuery::getPlayerTeamList($team->team_id),
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['player_age' => SORT_ASC],
                        'desc' => ['player_age' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['player_country_id' => SORT_ASC],
                        'desc' => ['player_country_id' => SORT_DESC],
                    ],
                    'game_row' => [
                        'asc' => ['player_game_row' => SORT_ASC],
                        'desc' => ['player_game_row' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['player_position_id' => SORT_ASC, 'player_id' => SORT_ASC],
                        'desc' => ['player_position_id' => SORT_DESC, 'player_id' => SORT_DESC],
                    ],
                    'physical' => [
                        'asc' => [$team->myTeam() ? 'player_physical_id' : 'player_position_id' => SORT_ASC],
                        'desc' => [$team->myTeam() ? 'player_physical_id' : 'player_position_id' => SORT_DESC],
                    ],
                    'power_nominal' => [
                        'asc' => ['player_power_nominal' => SORT_ASC],
                        'desc' => ['player_power_nominal' => SORT_DESC],
                    ],
                    'power_real' => [
                        'asc' => [$team->myTeam() ? 'player_power_real' : 'player_power_nominal' => SORT_ASC],
                        'desc' => [$team->myTeam() ? 'player_power_real' : 'player_power_nominal' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['player_price' => SORT_ASC],
                        'desc' => ['player_price' => SORT_DESC],
                    ],
                    'squad' => [
                        'asc' => ['player_squad_id' => SORT_ASC, 'player_position_id' => SORT_ASC],
                        'desc' => ['player_squad_id' => SORT_DESC, 'player_position_id' => SORT_ASC],
                    ],
                    'tire' => [
                        'asc' => [$team->myTeam() ? 'player_tire' : 'player_position_id' => SORT_ASC],
                        'desc' => [$team->myTeam() ? 'player_tire' : 'player_position_id' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['position' => SORT_ASC],
            ],
        ]);
    }
}
