<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\News;
use common\models\db\PreNews;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\TournamentType;
use common\models\db\User;
use Yii;
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
            $day = Yii::t('console', 'models.generator.insert-news.sunday');
        } elseif (1 === $day) {
            $day = Yii::t('console', 'models.generator.insert-news.monday');
        } elseif (2 === $day) {
            $day = Yii::t('console', 'models.generator.insert-news.tuesday');
        } elseif (3 === $day) {
            $day = Yii::t('console', 'models.generator.insert-news.wednesday');
        } elseif (4 === $day) {
            $day = Yii::t('console', 'models.generator.insert-news.thursday');
        } elseif (5 === $day) {
            $day = Yii::t('console', 'models.generator.insert-news.friday');
        } else {
            $day = Yii::t('console', 'models.generator.insert-news.saturday');
        }

        $title = Yii::t('console', 'models.generator.insert-news.title');
        $text = '';

        if ($today) {
            $text .= '<p class="strong">' . Yii::t('console', 'models.generator.insert-news.today', ['today' => $today]);

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
                $text .= '<p>' . Yii::t('console', 'models.generator.insert-news.score') . ' '
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
                $text .= '<p>' . Yii::t('console', 'models.generator.insert-news.power') . ' '
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
            $text .= '<p class="strong">' . Yii::t('console', 'models.generator.insert-news.tomorrow', ['day' => $day, 'tomorrow' => $tomorrow]);
        }

        $preNews = PreNews::find()
            ->where(['id' => 1])
            ->one();
        if ($preNews->error) {
            $text .= '<p class="strong">' . Yii::t('console', 'models.generator.insert-news.error') . '</p>' . "\r\n" . $preNews->error . "\r\n";
        }

        if ($preNews->new) {
            $text .= '<p class="strong">' . Yii::t('console', 'models.generator.insert-news.new') . '</p>' . "\r\n" . $preNews->new . "\r\n";
        }

        $preNews->error = '';
        $preNews->new = '';
        $preNews->save();

        $model = new News();
        $model->check = time();
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
            $before = Yii::t('console', 'models.generator.insert-news.payed');
        } else {
            $before = Yii::t('console', 'models.generator.insert-news.will-played');
        }

        foreach ($scheduleArray as $schedule) {
            $text = '';
            $stageName = $this->stageName($schedule->stage_id);
            if (TournamentType::NATIONAL === $schedule->tournament_type_id) {
                $text = Yii::t('console', 'models.generator.insert-news.world-cup', ['stage' => $stageName]);
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id) {
                if ($schedule->stage_id <= Stage::TOUR_LEAGUE_1 && $schedule->stage_id <= Stage::TOUR_LEAGUE_6) {
                    $text = Yii::t('console', 'models.generator.insert-news.league', ['stage' => $stageName]);
                } elseif ($schedule->stage_id < Stage::QUARTER) {
                    $text = Yii::t('console', 'models.generator.insert-news.league', ['stage' => $stageName]);
                } elseif ($schedule->stage_id < Stage::FINAL_GAME) {
                    $text = Yii::t('console', 'models.generator.insert-news.league-playoff', ['stage' => $stageName]);
                } elseif (Stage::FINAL_GAME === $schedule->stage_id) {
                    if ($today) {
                        $before = Yii::t('console', 'models.generator.insert-news.final-played');
                    } else {
                        $before = Yii::t('console', 'models.generator.insert-news.final-will-played');
                    }
                    $text = Yii::t('console', 'models.generator.insert-news.league-final', ['stage' => $stageName]);
                }
            } elseif (TournamentType::CHAMPIONSHIP === $schedule->tournament_type_id) {
                $text = Yii::t('console', 'models.generator.insert-news.championship', ['stage' => $stageName]);
            } elseif (TournamentType::CONFERENCE === $schedule->tournament_type_id) {
                $text = Yii::t('console', 'models.generator.insert-news.conference', ['stage' => $stageName]);
            } elseif (TournamentType::OFF_SEASON === $schedule->tournament_type_id) {
                $text = Yii::t('console', 'models.generator.insert-news.off-season', ['stage' => $stageName]);
            } elseif (TournamentType::FRIENDLY === $schedule->tournament_type_id) {
                $text = Yii::t('console', 'models.generator.insert-news.friendly');
            }
            $result[] = Html::a($text, ['schedule/view', 'id' => $schedule->id]);
        }

        $result = $before . ' ' . implode(' ' . Yii::t('console', 'models.generator.insert-news.and') . ' ', $result);

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
            $result = Yii::t('console', 'models.generator.insert-news.stage.1');
        } elseif (Stage::TOUR_2 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.2');
        } elseif (Stage::TOUR_3 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.3');
        } elseif (Stage::TOUR_4 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.4');
        } elseif (Stage::TOUR_5 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.5');
        } elseif (Stage::TOUR_6 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.6');
        } elseif (Stage::TOUR_7 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.7');
        } elseif (Stage::TOUR_8 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.8');
        } elseif (Stage::TOUR_9 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.9');
        } elseif (Stage::TOUR_10 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.10');
        } elseif (Stage::TOUR_11 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.11');
        } elseif (Stage::TOUR_12 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.12');
        } elseif (Stage::TOUR_13 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.13');
        } elseif (Stage::TOUR_14 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.14');
        } elseif (Stage::TOUR_15 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.15');
        } elseif (Stage::TOUR_16 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.16');
        } elseif (Stage::TOUR_17 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.17');
        } elseif (Stage::TOUR_18 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.18');
        } elseif (Stage::TOUR_19 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.19');
        } elseif (Stage::TOUR_20 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.20');
        } elseif (Stage::TOUR_21 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.21');
        } elseif (Stage::TOUR_22 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.22');
        } elseif (Stage::TOUR_23 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.23');
        } elseif (Stage::TOUR_24 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.24');
        } elseif (Stage::TOUR_25 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.25');
        } elseif (Stage::TOUR_26 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.26');
        } elseif (Stage::TOUR_27 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.27');
        } elseif (Stage::TOUR_28 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.28');
        } elseif (Stage::TOUR_29 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.29');
        } elseif (Stage::TOUR_30 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.30');
        } elseif (Stage::QUALIFY_1 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.q1');
        } elseif (Stage::QUALIFY_2 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.q2');
        } elseif (Stage::QUALIFY_3 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.q3');
        } elseif (Stage::TOUR_LEAGUE_1 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.1');
        } elseif (Stage::TOUR_LEAGUE_2 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.2');
        } elseif (Stage::TOUR_LEAGUE_3 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.3');
        } elseif (Stage::TOUR_LEAGUE_4 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.4');
        } elseif (Stage::TOUR_LEAGUE_5 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.5');
        } elseif (Stage::TOUR_LEAGUE_6 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.6');
        } elseif (Stage::ROUND_OF_16 === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.r16');
        } elseif (Stage::QUARTER === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.quarter');
        } elseif (Stage::SEMI === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.semi');
        } elseif (Stage::FINAL_GAME === $stageId) {
            $result = Yii::t('console', 'models.generator.insert-news.stage.final');
        }

        return $result;
    }
}
