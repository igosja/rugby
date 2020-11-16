<?php

// TODO refactor

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
            'query' => PlayerQuery::getPlayerTeamList($team->id),
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['age' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['age' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['country_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['country_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'game_row' => [
                        'asc' => ['game_row' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['game_row' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'physical' => [
                        'asc' => [$team->myTeam() ? 'physical_id' : 'position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => [$team->myTeam() ? 'physical_id' : 'position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'power_nominal' => [
                        'asc' => ['power_nominal' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'power_real' => [
                        'asc' => [$team->myTeam() ? 'power_real' : 'position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => [$team->myTeam() ? 'power_real' : 'position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['price' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['price' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'squad' => [
                        'asc' => ['squad_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['squad_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'tire' => [
                        'asc' => [$team->myTeam() ? 'tire' : 'position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => [$team->myTeam() ? 'tire' : 'position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['position' => SORT_ASC],
            ],
        ]);
    }
}
