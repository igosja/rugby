<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\RatingChapter;
use common\models\db\RatingFederation;
use common\models\db\RatingTeam;
use common\models\db\RatingType;
use common\models\db\RatingUser;
use common\models\db\Season;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;

/**
 * @var array $countryArray
 * @var integer $countryId
 * @var ActiveDataProvider $dataProvider
 * @var RatingType $ratingType
 * @var array $ratingTypeArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Рейтинги</h1>
    </div>
</div>
<?= Html::beginForm(['rating/index'], 'get') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Html::dropDownList(
            'id',
            $ratingType->id,
            $ratingTypeArray,
            ['class' => 'form-control submit-on-change', 'id' => 'ratingTypeId']
        ) ?>
    </div>
</div>
<?php if (RatingChapter::TEAM === $ratingType->rating_chapter_id): ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Html::dropDownList(
                'countryId',
                $countryId,
                $countryArray,
                ['class' => 'form-control submit-on-change', 'id' => 'countryId', 'prompt' => 'Все']
            ) ?>
        </div>
    </div>
<?php endif ?>
<?= Html::endForm() ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'class' => SerialColumn::class,
                'contentOptions' => ['class' => 'col-5 text-center'],
                'footer' => '№',
                'header' => '№',
                'headerOptions' => ['class' => 'col-5'],
            ],
        ];

        if (RatingChapter::TEAM === $ratingType->rating_chapter_id) {
            $columns[] = [
                'attribute' => 'team_name',
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => static function (RatingTeam $model) {
                    return $model->team->getTeamImageLink();
                }
            ];

            if (RatingType::TEAM_POWER === $ratingType->id) {
                $columns[] = [
                    'attribute' => 's_15',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 's15',
                    'footerOptions' => ['title' => 'Сумма сил 15 лучшых игроков'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Сумма сил 15 лучшых игроков'],
                    'label' => 's15',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_s_15;
                    }
                ];
                $columns[] = [
                    'attribute' => 's_19',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 's19',
                    'footerOptions' => ['title' => 'Сумма сил 19 лучших игроков'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Сумма сил 19 лучших игроков'],
                    'label' => 's19',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_s_19;
                    }
                ];
                $columns[] = [
                    'attribute' => 's_24',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 's24',
                    'footerOptions' => ['title' => 'Сумма сил 24 лучших игроков'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Сумма сил 24 лучших игроков'],
                    'label' => 's24',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_s_24;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Vs',
                    'footerOptions' => ['title' => 'Рейтинг силы команды в длительных соревнованиях'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Рейтинг силы команды в длительных соревнованиях'],
                    'label' => 'Vs',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_vs;
                    }
                ];
            } elseif (RatingType::TEAM_AGE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'В',
                    'footerOptions' => ['title' => 'Средний возраст'],
                    'headerOptions' => ['class' => 'col-15', 'title' => 'Средний возраст'],
                    'label' => 'В',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->player_average_age;
                    }
                ];
            } elseif (RatingType::TEAM_STADIUM === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Вм',
                    'footerOptions' => ['title' => 'Вместимость'],
                    'headerOptions' => ['class' => 'col-15', 'title' => 'Вместимость'],
                    'label' => 'Вм',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->stadium->capacity;
                    }
                ];
            } elseif (RatingType::TEAM_VISITOR === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Пос',
                    'footerOptions' => ['title' => 'Посещаемость'],
                    'headerOptions' => ['class' => 'col-15', 'title' => 'Посещаемость'],
                    'label' => 'Пос',
                    'value' => static function (RatingTeam $model) {
                        return Yii::$app->formatter->asDecimal($model->team->visitor / 100, 2);
                    }
                ];
            } elseif (RatingType::TEAM_BASE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'base',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Б',
                    'footerOptions' => ['title' => 'База'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'База'],
                    'label' => 'Б',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->base->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'П',
                    'footerOptions' => ['title' => 'Количество построек'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'Количество построек'],
                    'label' => 'П',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseUsed();
                    }
                ];
                $columns[] = [
                    'attribute' => 'training',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Т',
                    'footerOptions' => ['title' => 'Тренировочная база'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'Тренировочная база'],
                    'label' => 'Т',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseTraining->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'medical',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'М',
                    'footerOptions' => ['title' => 'Медицинский центр'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'Медицинский центр'],
                    'label' => 'М',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseMedical->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'physical',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Ф',
                    'footerOptions' => ['title' => 'Физцентр'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'Физцентр'],
                    'label' => 'Ф',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->basePhysical->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'school',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Сп',
                    'footerOptions' => ['title' => 'Спротшкола'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'Спротшкола'],
                    'label' => 'Сп',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseSchool->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'scout',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Ск',
                    'footerOptions' => ['title' => 'Скаутцентр'],
                    'headerOptions' => ['class' => 'col-6', 'title' => 'Скаутцентр'],
                    'label' => 'Ск',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseScout->level;
                    }
                ];
            } elseif (RatingType::TEAM_SALARY === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'ЗП',
                    'headerOptions' => ['class' => 'col-15', 'title' => 'Зарплата игроков'],
                    'label' => 'ЗП',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->salary);
                    }
                ];
            } elseif (RatingType::TEAM_FINANCE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => '$',
                    'headerOptions' => ['class' => 'col-15', 'title' => 'Денег в кассе'],
                    'label' => '$',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->finance);
                    }
                ];
            } elseif (RatingType::TEAM_PRICE_BASE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'База',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'База',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_base);
                    }
                ];
            } elseif (RatingType::TEAM_PRICE_STADIUM === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Стадион',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'Стадион',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_stadium);
                    }
                ];
            } elseif (RatingType::TEAM_PLAYER === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'player_number',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Кол',
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Количество'],
                    'label' => 'Кол',
                    'value' => static function (RatingTeam $model) {
                        return $model->team->player_number;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => '$',
                    'headerOptions' => ['class' => 'col-15', 'title' => 'Стоимость'],
                    'label' => '$',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_player);
                    }
                ];
            } elseif (RatingType::TEAM_PRICE_TOTAL === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'base_price',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'База',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'База',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_base);
                    }
                ];
                $columns[] = [
                    'attribute' => 'stadium_price',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Стадион',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'Стадион',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_stadium);
                    }
                ];
                $columns[] = [
                    'attribute' => 'player_price',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Игроки',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'Игроки',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_player);
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Стоимость',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'Стоимость',
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_total);
                    }
                ];
            }
        } elseif (RatingType::USER_RATING === $ratingType->id) {
            $columns[] = [
                'footer' => 'Менеджер',
                'format' => 'raw',
                'label' => 'Менеджер',
                'value' => static function (RatingUser $model) {
                    return $model->user->getUserLink();
                }
            ];
            $columns[] = [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Страна'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1', 'title' => 'Страна'],
                'label' => 'С',
                'value' => static function (RatingUser $model) {
                    return $model->user->country ? $model->user->country->getImage() : '';
                }
            ];
            $columns[] = [
                'attribute' => 'val',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Рейтинг',
                'headerOptions' => ['class' => 'col-15'],
                'label' => 'Рейтинг',
                'value' => static function (RatingUser $model) {
                    return $model->user->rating;
                }
            ];
        } else {
            $columns[] = [
                'footer' => 'Страна',
                'format' => 'raw',
                'label' => 'Страна',
                'value' => static function (RatingFederation $model) {
                    return $model->federation->country->getImageTextLink();
                }
            ];

            if (RatingType::FEDERATION_STADIUM === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => '10 лучших',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => '10 лучших',
                    'value' => static function (RatingFederation $model) {
                        return $model->federation->stadium_capacity;
                    }
                ];
            } elseif (RatingType::FEDERATION_AUTO === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'game',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'И',
                    'footerOptions' => ['title' => 'Игры'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Игры'],
                    'label' => 'И',
                    'value' => static function (RatingFederation $model) {
                        return $model->federation->game;
                    }
                ];
                $columns[] = [
                    'attribute' => 'auto',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'А',
                    'footerOptions' => ['title' => 'Автосоставы'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Автосоставы'],
                    'label' => 'А',
                    'value' => static function (RatingFederation $model) {
                        return $model->federation->auto;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => '%',
                    'headerOptions' => ['class' => 'col-10'],
                    'label' => '%',
                    'value' => static function (RatingFederation $model) {
                        return Yii::$app->formatter->asDecimal(
                            round($model->federation->auto / ($model->federation->game ?: 1) * 100, 1)
                        );
                    }
                ];
            } elseif (RatingType::FEDERATION_LEAGUE === $ratingType->id) {
                $season = Season::getCurrentSeason();
                for ($i = 4; $i >= 0; $i--) {
                    $columnSeason = $season - $i;

                    if ($columnSeason < 2) {
                        continue;
                    }

                    $columns[] = [
                        'contentOptions' => ['class' => 'text-center'],
                        'footer' => $columnSeason,
                        'footerOptions' => ['title' => 'Сезон ' . $columnSeason],
                        'headerOptions' => ['class' => 'col-10', 'title' => 'Сезон ' . $columnSeason],
                        'label' => $columnSeason,
                        'value' => static function (RatingFederation $model) use ($columnSeason) {
                            $count = 0;
                            $result = 0;
                            foreach ($model->federation->leagueCoefficients as $leagueCoefficient) {
                                if ($columnSeason === $leagueCoefficient->season_id) {
                                    $count++;
                                    $result += $leagueCoefficient->point;
                                }
                            }
                            if (!$count) {
                                $count = 1;
                            }
                            return Yii::$app->formatter->asDecimal($result / $count, 4);
                        }
                    ];
                }
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'K',
                    'footerOptions' => ['title' => 'Коэффициент'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Коэффициент'],
                    'label' => 'K',
                    'value' => static function (RatingFederation $model) use ($season) {
                        $rating = 0;
                        for ($i = 0; $i < 5; $i++) {
                            $count = 0;
                            $result = 0;
                            foreach ($model->federation->leagueCoefficients as $leagueCoefficient) {
                                if ($season - $i === $leagueCoefficient->season_id) {
                                    $count++;
                                    $result += $leagueCoefficient->point;
                                }
                            }
                            if (!$count) {
                                $count = 1;
                            }
                            $rating = $rating + $result / $count;
                        }
                        return Yii::$app->formatter->asDecimal($rating, 4);
                    }
                ];
            }
        }
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
