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
    public function actionGameStatistic(): string
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

        $this->view->title = 'Игровая статистика';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('game-statistic', [
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
                'name' => 'Средний размер базы',
                'select' => 'base',
            ),
            2 => array(
                'id' => 2,
                'name' => 'Средний размер баз (все строения)',
                'select' => 'base_total',
            ),
            3 => array(
                'id' => 3,
                'name' => 'Средний размер мед. центра',
                'select' => 'base_medical',
            ),
            4 => array(
                'id' => 4,
                'name' => 'Средний размер физиоцентра',
                'select' => 'base_physical',
            ),
            5 => array(
                'id' => 5,
                'name' => 'Средний размер спортшколы',
                'select' => 'base_school',
            ),
            6 => array(
                'id' => 6,
                'name' => 'Средний размер скаутцентра',
                'select' => 'base_scout',
            ),
            7 => array(
                'id' => 7,
                'name' => 'Средний размер трен. центра',
                'select' => 'base_training',
            ),
            8 => array(
                'id' => 8,
                'name' => 'Всего федераций',
                'select' => 'federation',
            ),
            9 => array(
                'id' => 9,
                'name' => 'Всего менеджеров',
                'select' => 'manager',
            ),
            10 => array(
                'id' => 10,
                'name' => 'VIP менеджеров',
                'select' => 'manager_vip',
            ),
            11 => array(
                'id' => 11,
                'name' => 'Менеджеров с командами',
                'select' => 'manager_with_team',
            ),
            12 => array(
                'id' => 12,
                'name' => 'Число игроков в командах',
                'select' => 'player',
            ),
            13 => array(
                'id' => 13,
                'name' => 'Средний возраст игрока',
                'select' => 'player_age',
            ),
            16 => array(
                'id' => 16,
                'name' => 'Игроков в команде в среднем',
                'select' => 'player_in_team',
            ),
            14 => array(
                'id' => 14,
                'name' => 'Пизиция C',
                'select' => 'player_position_centre',
            ),
            15 => array(
                'id' => 15,
                'name' => 'Пизиция 8',
                'select' => 'player_position_eight',
            ),
            43 => array(
                'id' => 43,
                'name' => 'Пизиция FL',
                'select' => 'player_position_flanker',
            ),
            44 => array(
                'id' => 44,
                'name' => 'Пизиция FH',
                'select' => 'player_position_fly_half',
            ),
            19 => array(
                'id' => 19,
                'name' => 'Пизиция FB',
                'select' => 'player_position_full_back',
            ),
            45 => array(
                'id' => 45,
                'name' => 'Пизиция H',
                'select' => 'player_position_hooker',
            ),
            46 => array(
                'id' => 46,
                'name' => 'Пизиция L',
                'select' => 'player_position_lock',
            ),
            47 => array(
                'id' => 47,
                'name' => 'Пизиция P',
                'select' => 'player_position_prop',
            ),
            48 => array(
                'id' => 48,
                'name' => 'Пизиция SH',
                'select' => 'player_position_scrum_half',
            ),
            49 => array(
                'id' => 49,
                'name' => 'Пизиция W',
                'select' => 'player_position_wing',
            ),
            21 => array(
                'id' => 21,
                'name' => 'Средняя сила игрока',
                'select' => 'player_power',
            ),
            22 => array(
                'id' => 22,
                'name' => 'Игроков без спецвозможностей',
                'select' => 'player_special_no',
            ),
            23 => array(
                'id' => 23,
                'name' => 'Игроков с одной спецвозможностью',
                'select' => 'player_special_one',
            ),
            24 => array(
                'id' => 24,
                'name' => 'Игроков с двумя спецвозможностями',
                'select' => 'player_special_two',
            ),
            25 => array(
                'id' => 25,
                'name' => 'Игроков с тремя спецвозможностями',
                'select' => 'player_special_three',
            ),
            26 => array(
                'id' => 26,
                'name' => 'Игроков с четырьмя спецвозможностями',
                'select' => 'player_special_four',
            ),
            27 => array(
                'id' => 27,
                'name' => 'Игроков со спецвозможностью Атлетизм (Ат)',
                'select' => 'player_special_athletic',
            ),
            28 => array(
                'id' => 28,
                'name' => 'Игроков со спецвозможностью Комбинирование (Км)',
                'select' => 'player_special_combine',
            ),
            29 => array(
                'id' => 29,
                'name' => 'Игроков со спецвозможностью Кумир (К)',
                'select' => 'player_special_idol',
            ),
            30 => array(
                'id' => 30,
                'name' => 'Игроков со спецвозможностью Лидер (Л)',
                'select' => 'player_special_leader',
            ),
            31 => array(
                'id' => 31,
                'name' => 'Игроков со спецвозможностью Мол (М)',
                'select' => 'player_special_moul',
            ),
            32 => array(
                'id' => 32,
                'name' => 'Игроков со спецвозможностью Пас (П)',
                'select' => 'player_special_pass',
            ),
            33 => array(
                'id' => 33,
                'name' => 'Игроков со спецвозможностью Сила (Сл)',
                'select' => 'player_special_power',
            ),
            34 => array(
                'id' => 34,
                'name' => 'Игроков со спецвозможностью Рак (Р)',
                'select' => 'player_special_ruck',
            ),
            35 => array(
                'id' => 35,
                'name' => 'Игроков со спецвозможностью Схватка (Сх)',
                'select' => 'player_special_scrum',
            ),
            36 => array(
                'id' => 36,
                'name' => 'Игроков со спецвозможностью Скорость (Ск)',
                'select' => 'player_special_speed',
            ),
            37 => array(
                'id' => 37,
                'name' => 'Игроков со спецвозможностью Отбор (От)',
                'select' => 'player_special_tackle',
            ),
            38 => array(
                'id' => 38,
                'name' => 'Игроков с совмещениями',
                'select' => 'player_with_position',
            ),
            39 => array(
                'id' => 39,
                'name' => 'Всего команд',
                'select' => 'team',
            ),
            40 => array(
                'id' => 40,
                'name' => 'Денег в среднем в кассе команды',
                'select' => 'team_finance',
            ),
            41 => array(
                'id' => 41,
                'name' => 'Среднее число команд у менеджеров',
                'select' => 'team_to_manager',
            ),
            42 => array(
                'id' => 42,
                'name' => 'Средний размер стадиона',
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

        $this->view->title = 'Статистические данные';
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
