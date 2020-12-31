<?php

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
                'select' => 'snapshot_base',
            ),
            2 => array(
                'id' => 2,
                'name' => 'Средний размер баз (все строения)',
                'select' => 'snapshot_base_total',
            ),
            3 => array(
                'id' => 3,
                'name' => 'Средний размер мед. центра',
                'select' => 'snapshot_base_medical',
            ),
            4 => array(
                'id' => 4,
                'name' => 'Средний размер физиоцентра',
                'select' => 'snapshot_base_physical',
            ),
            5 => array(
                'id' => 5,
                'name' => 'Средний размер спортшколы',
                'select' => 'snapshot_base_school',
            ),
            6 => array(
                'id' => 6,
                'name' => 'Средний размер скаутцентра',
                'select' => 'snapshot_base_scout',
            ),
            7 => array(
                'id' => 7,
                'name' => 'Средний размер трен. центра',
                'select' => 'snapshot_base_training',
            ),
            8 => array(
                'id' => 8,
                'name' => 'Всего федераций',
                'select' => 'snapshot_country',
            ),
            9 => array(
                'id' => 9,
                'name' => 'Всего менеджеров',
                'select' => 'snapshot_manager',
            ),
            10 => array(
                'id' => 10,
                'name' => 'VIP менеджеров',
                'select' => 'snapshot_manager_vip_percent',
            ),
            11 => array(
                'id' => 11,
                'name' => 'Менеджеров с командами',
                'select' => 'snapshot_manager_with_team',
            ),
            12 => array(
                'id' => 12,
                'name' => 'Число игроков в командах',
                'select' => 'snapshot_player',
            ),
            13 => array(
                'id' => 13,
                'name' => 'Средний возраст игрока',
                'select' => 'snapshot_player_age',
            ),
            14 => array(
                'id' => 14,
                'name' => 'Пизиция C',
                'select' => 'snapshot_player_c',
            ),
            15 => array(
                'id' => 15,
                'name' => 'Пизиция GK',
                'select' => 'snapshot_player_gk',
            ),
            16 => array(
                'id' => 16,
                'name' => 'Игроков в команде в среднем',
                'select' => 'snapshot_player_in_team',
            ),
            17 => array(
                'id' => 17,
                'name' => 'Пизиция LD',
                'select' => 'snapshot_player_ld',
            ),
            18 => array(
                'id' => 18,
                'name' => 'Пизиция LW',
                'select' => 'snapshot_player_lw',
            ),
            19 => array(
                'id' => 19,
                'name' => 'Пизиция RD',
                'select' => 'snapshot_player_rd',
            ),
            20 => array(
                'id' => 20,
                'name' => 'Пизиция RW',
                'select' => 'snapshot_player_rw',
            ),
            21 => array(
                'id' => 21,
                'name' => 'Средняя сила игрока',
                'select' => 'snapshot_player_power',
            ),
            22 => array(
                'id' => 22,
                'name' => 'Игроков без спецвозможностей',
                'select' => 'snapshot_player_special_percent_no',
            ),
            23 => array(
                'id' => 23,
                'name' => 'Игроков с одной спецвозможностью',
                'select' => 'snapshot_player_special_percent_one',
            ),
            24 => array(
                'id' => 24,
                'name' => 'Игроков с двумя спецвозможностями',
                'select' => 'snapshot_player_special_percent_two',
            ),
            25 => array(
                'id' => 25,
                'name' => 'Игроков с тремя спецвозможностями',
                'select' => 'snapshot_player_special_percent_three',
            ),
            26 => array(
                'id' => 26,
                'name' => 'Игроков с четырьмя спецвозможностями',
                'select' => 'snapshot_player_special_percent_four',
            ),
            27 => array(
                'id' => 27,
                'name' => 'Игроков со спецвозможностью Атлетизм (Ат)',
                'select' => 'snapshot_player_special_percent_athletic',
            ),
            28 => array(
                'id' => 28,
                'name' => 'Игроков со спецвозможностью Техника (Т)',
                'select' => 'snapshot_player_special_percent_combine',
            ),
            29 => array(
                'id' => 29,
                'name' => 'Игроков со спецвозможностью Кумир (К)',
                'select' => 'snapshot_player_special_percent_idol',
            ),
            30 => array(
                'id' => 30,
                'name' => 'Игроков со спецвозможностью Лидер (Л)',
                'select' => 'snapshot_player_special_percent_leader',
            ),
            31 => array(
                'id' => 31,
                'name' => 'Игроков со спецвозможностью Силовая борьба (Сб)',
                'select' => 'snapshot_player_special_percent_power',
            ),
            32 => array(
                'id' => 32,
                'name' => 'Игроков со спецвозможностью Реакция (Р)',
                'select' => 'snapshot_player_special_percent_reaction',
            ),
            33 => array(
                'id' => 33,
                'name' => 'Игроков со спецвозможностью Бросок (Бр)',
                'select' => 'snapshot_player_special_percent_shot',
            ),
            34 => array(
                'id' => 34,
                'name' => 'Игроков со спецвозможностью Скорость (Ск)',
                'select' => 'snapshot_player_special_percent_speed',
            ),
            35 => array(
                'id' => 35,
                'name' => 'Игроков со спецвозможностью Отбор (От)',
                'select' => 'snapshot_player_special_percent_tackle',
            ),
            36 => array(
                'id' => 36,
                'name' => 'Игроков со спецвозможностью Игра клюшкой (Кл)',
                'select' => 'snapshot_player_special_percent_stick',
            ),
            37 => array(
                'id' => 37,
                'name' => 'Игроков со спецвозможностью Выбор позиции (П)',
                'select' => 'snapshot_player_special_percent_position',
            ),
            38 => array(
                'id' => 38,
                'name' => 'Игроков с совмещениями',
                'select' => 'snapshot_player_with_position_percent',
            ),
            39 => array(
                'id' => 39,
                'name' => 'Всего команд',
                'select' => 'snapshot_team',
            ),
            40 => array(
                'id' => 40,
                'name' => 'Денег в среднем в кассе команды',
                'select' => 'snapshot_team_finance',
            ),
            41 => array(
                'id' => 41,
                'name' => 'Среднее число команд у менеджеров',
                'select' => 'snapshot_team_to_manager',
            ),
            42 => array(
                'id' => 42,
                'name' => 'Средний размер стадиона',
                'select' => 'snapshot_stadium',
            ),
        );

        $seasonId = Yii::$app->request->get('seasonId', Season::getCurrentSeason());

        $date_array = array();
        $value_array = array();

        $snapshotArray = Snapshot::find()
            ->select([
                'date' => new Expression('FROM_UNIXTIME(`snapshot_date`, \'%d %m %Y\')'),
                'total' => $category_array[$id]['select'],
            ])
            ->where(['snapshot_season_id' => $seasonId])
            ->orderBy(['snapshot_id' => SORT_ASC])
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
