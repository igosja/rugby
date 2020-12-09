<?php

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\News;
use common\models\db\PreNews;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\TournamentType;
use common\models\db\User;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\Html;

/**
 * Class InsertNews
 * @package console\models\generator
 */
class InsertNews
{
    /**
     * @return void
     * @throws Exception
     * @throws \Exception
     */
    public function execute(): void
    {
        $todayArray = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $today = $this->text($todayArray, true);

        $tomorrowArray = Schedule::find()
            ->where('FROM_UNIXTIME(`date`-86400, "%Y-%m-%d")=CURDATE()')
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $tomorrow = $this->text($tomorrowArray);

        $day = (int)date('w', strtotime('+1day'));

        if (0 === $day) {
            $day = 'воскресенье';
        } elseif (1 === $day) {
            $day = 'понедельник';
        } elseif (2 === $day) {
            $day = 'вторник';
        } elseif (3 === $day) {
            $day = 'среду';
        } elseif (4 === $day) {
            $day = 'четверг';
        } elseif (5 === $day) {
            $day = 'пятницу';
        } else {
            $day = 'субботу';
        }

        $title = 'Вести с арен';
        $text = '';

        if ($today) {
            $text .= '<p class="strong">СЕГОДНЯ</p>' . "\r\n" . '<p>Сегодня ' . $today . '.</p>' . "\r\n";

            /**
             * @var Game $game
             */
            $game = Game::find()
                ->andWhere([
                    'schedule_id' => Schedule::find()
                        ->select('id')
                        ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                ])
                ->orderBy(new Expression('guest_point+home_point DESC'))
                ->limit(1)
                ->one();

            if ($game) {
                $text .= '<p>Самый крупный счёт в этот день был зафиксирован в матче '
                    . $game->teamOrNationalLink('home')
                    . ' - '
                    . $game->teamOrNationalLink('guest')
                    . ' - '
                    . Html::a(
                        $game->home_point . ':' . $game->guest_point,
                        ['game/view', 'id' => $game->id]
                    )
                    . '</p>'
                    . "\r\n";
            }

            $game = Game::find()
                ->andWhere([
                    'schedule_id' => Schedule::find()
                        ->select('id')
                        ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                ])
                ->orderBy(new Expression('guest_power+home_power DESC'))
                ->limit(1)
                ->one();

            if ($game) {
                $text .= '<p>Самую большую суммарную силу соперников зрители могли увидеть в матче '
                    . $game->teamOrNationalLink('home')
                    . ' - '
                    . $game->teamOrNationalLink('guest')
                    . ' - '
                    . Html::a(
                        $game->home_point . ':' . $game->guest_point,
                        ['game/view', 'id' => $game->id]
                    )
                    . '</p>'
                    . "\r\n";
            }
        }

        if ($tomorrow) {
            $text .= '<p class="strong">ЗАВТРА ДНЁМ</p>' . "\r\n" . '<p>В ' . $day . ' в Лиге ' . $tomorrow . '.</p>' . "\r\n";
        }

        $preNews = PreNews::find()
            ->where(['id' => 1])
            ->one();
        if ($preNews->error) {
            $text .= '<p class="strong">РАБОТА НАД ОШИБКАМИ</p>' . "\r\n" . $preNews->error . "\r\n";
        }

        if ($preNews->new) {
            $text .= '<p class="strong">НОВОЕ НА САЙТЕ</p>' . "\r\n" . $preNews->new . "\r\n";
        }

        $preNews->error = '';
        $preNews->new = '';
        $preNews->save();

        $model = new News();
        $model->text = $text;
        $model->title = $title;
        $model->user_id = User::ADMIN_USER_ID;
        $model->save();
    }

    /**
     * @param Schedule[] $scheduleArray
     * @param bool $today
     * @return string
     */
    private function text(array $scheduleArray, bool $today = false): string
    {
        $result = [];
        if ($today) {
            $before = 'состоялись';
        } else {
            $before = 'будут сыграны';
        }

        foreach ($scheduleArray as $schedule) {
            $text = '';
            $stageName = $this->stageName($schedule->stage_id);
            if (TournamentType::NATIONAL === $schedule->tournament_type_id) {
                $text = 'матчи ' . $stageName . ' Чемпионата мира среди сборных';
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id) {
                if ($schedule->stage_id <= Stage::TOUR_LEAGUE_1 && $schedule->stage_id <= Stage::TOUR_LEAGUE_6) {
                    $text = 'матчи ' . $stageName . ' Лиги чемпионов';
                } elseif ($schedule->stage_id < Stage::QUARTER) {
                    $text = 'матчи ' . $stageName . ' Лиги чемпионов';
                } elseif ($schedule->stage_id < Stage::FINAL_GAME) {
                    $text = $stageName . ' Лиги чемпионов';
                } elseif (Stage::FINAL_GAME === $schedule->stage_id) {
                    if ($today) {
                        $before = 'состоялся';
                    } else {
                        $before = 'будет сыгран';
                    }
                    $text = $stageName . ' Лиги чемпионов';
                }
            } elseif (TournamentType::CHAMPIONSHIP === $schedule->tournament_type_id) {
                if ($schedule->stage_id <= Stage::TOUR_30) {
                    $text = 'матчи ' . $stageName . ' национальных чемпионатов';
                } elseif ($schedule->stage_id <= Stage::FINAL_GAME) {
                    $text = $stageName . ' национальных чемпионатов';
                } elseif (Stage::FINAL_GAME === $schedule->stage_id) {
                    $text = $stageName . 'ы национальных чемпионатов';
                }
            } elseif (TournamentType::CONFERENCE === $schedule->tournament_type_id) {
                $text = 'матчи ' . $stageName . ' конференции любительских клубов';
            } elseif (TournamentType::OFF_SEASON === $schedule->tournament_type_id) {
                $text = 'матчи ' . $stageName . ' кубка межсезонья';
            } elseif (TournamentType::FRIENDLY === $schedule->tournament_type_id) {
                $text = 'товарищеские матчи';
            }
            $result[] = Html::a($text, ['schedule/view', 'id' => $schedule->id]);
        }

        $result = $before . ' ' . implode(' и ', $result);

        return $result;
    }

    /**
     * @param int $stageId
     * @return string
     */
    private function stageName(int $stageId): string
    {
        $result = '';
        if (Stage::TOUR_1 === $stageId) {
            $result = '1-го тура';
        } elseif (Stage::TOUR_2 === $stageId) {
            $result = '2-го тура';
        } elseif (Stage::TOUR_3 === $stageId) {
            $result = '3-го тура';
        } elseif (Stage::TOUR_4 === $stageId) {
            $result = '4-го тура';
        } elseif (Stage::TOUR_5 === $stageId) {
            $result = '5-го тура';
        } elseif (Stage::TOUR_6 === $stageId) {
            $result = '6-го тура';
        } elseif (Stage::TOUR_7 === $stageId) {
            $result = '7-го тура';
        } elseif (Stage::TOUR_8 === $stageId) {
            $result = '8-го тура';
        } elseif (Stage::TOUR_9 === $stageId) {
            $result = '9-го тура';
        } elseif (Stage::TOUR_10 === $stageId) {
            $result = '10-го тура';
        } elseif (Stage::TOUR_11 === $stageId) {
            $result = '11-го тура';
        } elseif (Stage::TOUR_12 === $stageId) {
            $result = '12-го тура';
        } elseif (Stage::TOUR_13 === $stageId) {
            $result = '13-го тура';
        } elseif (Stage::TOUR_14 === $stageId) {
            $result = '14-го тура';
        } elseif (Stage::TOUR_15 === $stageId) {
            $result = '15-го тура';
        } elseif (Stage::TOUR_16 === $stageId) {
            $result = '16-го тура';
        } elseif (Stage::TOUR_17 === $stageId) {
            $result = '17-го тура';
        } elseif (Stage::TOUR_18 === $stageId) {
            $result = '18-го тура';
        } elseif (Stage::TOUR_19 === $stageId) {
            $result = '19-го тура';
        } elseif (Stage::TOUR_20 === $stageId) {
            $result = '20-го тура';
        } elseif (Stage::TOUR_21 === $stageId) {
            $result = '21-го тура';
        } elseif (Stage::TOUR_22 === $stageId) {
            $result = '22-го тура';
        } elseif (Stage::TOUR_23 === $stageId) {
            $result = '23-го тура';
        } elseif (Stage::TOUR_24 === $stageId) {
            $result = '24-го тура';
        } elseif (Stage::TOUR_25 === $stageId) {
            $result = '25-го тура';
        } elseif (Stage::TOUR_26 === $stageId) {
            $result = '26-го тура';
        } elseif (Stage::TOUR_27 === $stageId) {
            $result = '27-го тура';
        } elseif (Stage::TOUR_28 === $stageId) {
            $result = '28-го тура';
        } elseif (Stage::TOUR_29 === $stageId) {
            $result = '29-го тура';
        } elseif (Stage::TOUR_30 === $stageId) {
            $result = '30-го тура';
        } elseif (Stage::QUALIFY_1 === $stageId) {
            $result = '1-го ОР';
        } elseif (Stage::QUALIFY_2 === $stageId) {
            $result = '2-го ОР';
        } elseif (Stage::QUALIFY_3 === $stageId) {
            $result = '3-го ОР';
        } elseif (Stage::TOUR_LEAGUE_1 === $stageId) {
            $result = '1-го тура';
        } elseif (Stage::TOUR_LEAGUE_2 === $stageId) {
            $result = '2-го тура';
        } elseif (Stage::TOUR_LEAGUE_3 === $stageId) {
            $result = '3-го тура';
        } elseif (Stage::TOUR_LEAGUE_4 === $stageId) {
            $result = '4-го тура';
        } elseif (Stage::TOUR_LEAGUE_5 === $stageId) {
            $result = '5-го тура';
        } elseif (Stage::TOUR_LEAGUE_6 === $stageId) {
            $result = '6-го тура';
        } elseif (Stage::ROUND_OF_16 === $stageId) {
            $result = '1/8 финала';
        } elseif (Stage::QUARTER === $stageId) {
            $result = 'четвертьфиналы';
        } elseif (Stage::SEMI === $stageId) {
            $result = 'полуфиналы';
        } elseif (Stage::FINAL_GAME === $stageId) {
            $result = 'финал';
        }

        return $result;
    }
}
