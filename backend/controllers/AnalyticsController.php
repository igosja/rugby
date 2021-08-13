<?php

// TODO refactor

namespace backend\controllers;

use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Snapshot;
use Yii;
use yii\db\Expression;

/**
 * Class AnalyticsController
 * @package backend\controllers
 */
class AnalyticsController extends AbstractController
{
    /**
     * @return string
     */
    public function actionGeneratorCorrection(): string
    {
        $game = Game::find()
            ->select([
                'home_point' => new Expression('AVG(`home_point`+`guest_point`) / 2'),
                'home_try' => new Expression('AVG(`home_try`+`guest_try`) / 2'),
                'home_penalty_kick' => new Expression('AVG(`home_penalty_kick`+`guest_penalty_kick`) / 2'),
                'home_drop_goal' => new Expression('AVG(`home_drop_goal`+`guest_drop_goal`) / 2'),
                'home_conversion' => new Expression('AVG(`home_conversion`+`guest_conversion`) / 2'),
                'home_yellow_card' => new Expression('AVG(`home_yellow_card`+`guest_yellow_card`) / 2'),
                'home_red_card' => new Expression('AVG(`home_red_card`+`guest_red_card`) / 2'),
            ])
            ->where(['not', ['played' => null]])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select(['id'])
                    ->where('date>UNIX_TIMESTAMP()-604800')
            ])
            ->asArray()
            ->one();

        $this->view->title = 'Generator correction';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('generator-correction', [
            'game' => $game,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionSnapshot(int $id = 1): string
    {
        $category_array = array(
            1 => array(
                'id' => 1,
                'name' => 'Average base size',
                'select' => 'base',
            ),
            2 => array(
                'id' => 2,
                'name' => 'Average base size (all buildings)',
                'select' => 'base_total',
            ),
            3 => array(
                'id' => 3,
                'name' => 'Average size of a medical center',
                'select' => 'base_medical',
            ),
            4 => array(
                'id' => 4,
                'name' => 'Average size of a physical center',
                'select' => 'base_physical',
            ),
            5 => array(
                'id' => 5,
                'name' => 'Average size of a sports school',
                'select' => 'base_school',
            ),
            6 => array(
                'id' => 6,
                'name' => 'Average size of a scout center',
                'select' => 'base_scout',
            ),
            7 => array(
                'id' => 7,
                'name' => 'Average size of a training center',
                'select' => 'base_training',
            ),
            8 => array(
                'id' => 8,
                'name' => 'Number of federations',
                'select' => 'federation',
            ),
            9 => array(
                'id' => 9,
                'name' => 'Number of managers',
                'select' => 'manager',
            ),
            10 => array(
                'id' => 10,
                'name' => 'Number of VIP managers',
                'select' => 'manager_vip',
            ),
            11 => array(
                'id' => 11,
                'name' => 'Number of managers with teams',
                'select' => 'manager_with_team',
            ),
            12 => array(
                'id' => 12,
                'name' => 'Number of players in teams',
                'select' => 'player',
            ),
            13 => array(
                'id' => 13,
                'name' => 'Average player age',
                'select' => 'player_age',
            ),
            16 => array(
                'id' => 16,
                'name' => 'Average number of players per team',
                'select' => 'player_in_team',
            ),
            14 => array(
                'id' => 14,
                'name' => 'Position C',
                'select' => 'player_position_centre',
            ),
            15 => array(
                'id' => 15,
                'name' => 'Position 8',
                'select' => 'player_position_eight',
            ),
            43 => array(
                'id' => 43,
                'name' => 'Position FL',
                'select' => 'player_position_flanker',
            ),
            44 => array(
                'id' => 44,
                'name' => 'Position FH',
                'select' => 'player_position_fly_half',
            ),
            19 => array(
                'id' => 19,
                'name' => 'Position FB',
                'select' => 'player_position_full_back',
            ),
            45 => array(
                'id' => 45,
                'name' => 'Position H',
                'select' => 'player_position_hooker',
            ),
            46 => array(
                'id' => 46,
                'name' => 'Position L',
                'select' => 'player_position_lock',
            ),
            47 => array(
                'id' => 47,
                'name' => 'Position P',
                'select' => 'player_position_prop',
            ),
            48 => array(
                'id' => 48,
                'name' => 'Position SH',
                'select' => 'player_position_scrum_half',
            ),
            49 => array(
                'id' => 49,
                'name' => 'Position W',
                'select' => 'player_position_wing',
            ),
            21 => array(
                'id' => 21,
                'name' => 'Average player power',
                'select' => 'player_power',
            ),
            22 => array(
                'id' => 22,
                'name' => 'Players without special abilities',
                'select' => 'player_special_no',
            ),
            23 => array(
                'id' => 23,
                'name' => 'Players with one special ability',
                'select' => 'player_special_one',
            ),
            24 => array(
                'id' => 24,
                'name' => 'Players with two special abilities',
                'select' => 'player_special_two',
            ),
            25 => array(
                'id' => 25,
                'name' => 'Players with three special abilities',
                'select' => 'player_special_three',
            ),
            26 => array(
                'id' => 26,
                'name' => 'Players with four special abilities',
                'select' => 'player_special_four',
            ),
            27 => array(
                'id' => 27,
                'name' => 'Players with special ability Athleticism (At)',
                'select' => 'player_special_athletic',
            ),
            28 => array(
                'id' => 28,
                'name' => 'Players with special ability Combining (Cm)',
                'select' => 'player_special_combine',
            ),
            29 => array(
                'id' => 29,
                'name' => 'Players with special ability Idol (I)',
                'select' => 'player_special_idol',
            ),
            30 => array(
                'id' => 30,
                'name' => 'Players with a special ability Leader (L)',
                'select' => 'player_special_leader',
            ),
            31 => array(
                'id' => 31,
                'name' => 'Players with special ability Moul (M)',
                'select' => 'player_special_moul',
            ),
            32 => array(
                'id' => 32,
                'name' => 'Players with special ability Pass (Ps)',
                'select' => 'player_special_pass',
            ),
            33 => array(
                'id' => 33,
                'name' => 'Players with special ability Power (Pw)',
                'select' => 'player_special_power',
            ),
            34 => array(
                'id' => 34,
                'name' => 'Players with special ability Ruck (R)',
                'select' => 'player_special_ruck',
            ),
            35 => array(
                'id' => 35,
                'name' => 'Players with special ability Scrum (Sc)',
                'select' => 'player_special_scrum',
            ),
            36 => array(
                'id' => 36,
                'name' => 'Players with special ability Speed (Sp)',
                'select' => 'player_special_speed',
            ),
            37 => array(
                'id' => 37,
                'name' => 'Players with special ability Tackle (T)',
                'select' => 'player_special_tackle',
            ),
            38 => array(
                'id' => 38,
                'name' => 'Players with combine positions',
                'select' => 'player_with_position',
            ),
            39 => array(
                'id' => 39,
                'name' => 'Number of teams',
                'select' => 'team',
            ),
            40 => array(
                'id' => 40,
                'name' => 'Average cash at the team\'s cash box',
                'select' => 'team_finance',
            ),
            41 => array(
                'id' => 41,
                'name' => 'Average number of teams per manager',
                'select' => 'team_to_manager',
            ),
            42 => array(
                'id' => 42,
                'name' => 'Average stadium size',
                'select' => 'stadium',
            ),
        );

        $seasonId = Yii::$app->request->get('seasonId', Season::getCurrentSeason());

        $date_array = array();
        $value_array = array();

        $snapshotArray = Snapshot::find()
            ->select([
                'date' => new Expression('FROM_UNIXTIME(`date`, \'%d %m %Y\')'),
                'total' => $category_array[$id]['select'],
            ])
            ->where(['season_id' => $seasonId])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();

        foreach ($snapshotArray as $item) {
            $date_array[] = $item['date'];
            $value_array[] = (float)$item['total'];
        }

        $seasonArray = Season::getSeasonArray();

        $this->view->title = 'Snapshots';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('snapshot', [
            'categoryArray' => $category_array,
            'dataArray' => $date_array,
            'id' => $id,
            'seasonArray' => $seasonArray,
            'seasonId' => $seasonId,
            'valueArray' => $value_array,
        ]);
    }
}
