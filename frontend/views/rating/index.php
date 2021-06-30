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
        <h1><?= Yii::t('frontend', 'views.rating.index.h1') ?></h1>
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
                ['class' => 'form-control submit-on-change', 'id' => 'countryId', 'prompt' => Yii::t('frontend', 'views.rating.index.prompt.country')]
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
                'footer' => '#',
                'header' => '#',
                'headerOptions' => ['class' => 'col-5'],
            ],
        ];

        if (RatingChapter::TEAM === $ratingType->rating_chapter_id) {
            $columns[] = [
                'attribute' => 'team_name',
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (RatingTeam $model) {
                    return $model->team->getTeamImageLink();
                }
            ];

            if (RatingType::TEAM_POWER === $ratingType->id) {
                $columns[] = [
                    'attribute' => 's_15',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.s15'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.s15')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.s15')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.s15'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_s_15;
                    }
                ];
                $columns[] = [
                    'attribute' => 's_19',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.s19'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.s19')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.s19')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.s19'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_s_19;
                    }
                ];
                $columns[] = [
                    'attribute' => 's_24',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.s24'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.s24')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.s24')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.s24'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_s_24;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.vs'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.vs')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.vs')],
                    'label' => Yii::t('frontend', 'views.th.vs'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->power_vs;
                    }
                ];
            } elseif (RatingType::TEAM_AGE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.age'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.age')],
                    'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.rating.index.title.age')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.age'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->player_average_age;
                    }
                ];
            } elseif (RatingType::TEAM_STADIUM === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.stadium'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.stadium')],
                    'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.rating.index.title.stadium')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.stadium'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->stadium->capacity;
                    }
                ];
            } elseif (RatingType::TEAM_VISITOR === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.visitor'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.visitor')],
                    'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.rating.index.title.visitor')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.visitor'),
                    'value' => static function (RatingTeam $model) {
                        return Yii::$app->formatter->asDecimal($model->team->visitor / 100, 2);
                    }
                ];
            } elseif (RatingType::TEAM_BASE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'base',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.base'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.base')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.title.base')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.base'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->base->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.base.used'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.base.used')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.title.base.used')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.base.used'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseUsed();
                    }
                ];
                $columns[] = [
                    'attribute' => 'training',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.training'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.training')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.title.training')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.training'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseTraining->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'medical',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.medical'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.medical')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.title.medical')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.medical'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseMedical->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'physical',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.physical'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.physical')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.title.physical')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.physical'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->basePhysical->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'school',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.school'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.school')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.title.school')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.school'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseSchool->level;
                    }
                ];
                $columns[] = [
                    'attribute' => 'scout',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.scout'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.scout')],
                    'headerOptions' => ['class' => 'col-6', 'title' => Yii::t('frontend', 'views.rating.index.th.scout')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.scout'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->baseScout->level;
                    }
                ];
            } elseif (RatingType::TEAM_SALARY === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.salary'),
                    'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.rating.index.title.salary')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.salary'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->salary);
                    }
                ];
            } elseif (RatingType::TEAM_FINANCE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.finance'),
                    'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.rating.index.title.finance')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.finance'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->finance);
                    }
                ];
            } elseif (RatingType::TEAM_PRICE_BASE === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.base'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.price.base'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_base);
                    }
                ];
            } elseif (RatingType::TEAM_PRICE_STADIUM === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.stadium'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.price.stadium'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_stadium);
                    }
                ];
            } elseif (RatingType::TEAM_PLAYER === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'player_number',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.player'),
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.player')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.player'),
                    'value' => static function (RatingTeam $model) {
                        return $model->team->player_number;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.player'),
                    'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.rating.index.title.price.player')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.player'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_player);
                    }
                ];
            } elseif (RatingType::TEAM_PRICE_TOTAL === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'base_price',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.base'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.price.base'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_base);
                    }
                ];
                $columns[] = [
                    'attribute' => 'stadium_price',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.stadium'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.price.stadium'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_stadium);
                    }
                ];
                $columns[] = [
                    'attribute' => 'player_price',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.total.player'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.price.total.player'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_player);
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.price.total'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.price.total'),
                    'value' => static function (RatingTeam $model) {
                        return FormatHelper::asCurrency($model->team->price_total);
                    }
                ];
            }
        } elseif (RatingType::USER_RATING === $ratingType->id) {
            $columns[] = [
                'footer' => Yii::t('frontend', 'views.rating.index.th.user'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.rating.index.th.user'),
                'value' => static function (RatingUser $model) {
                    return $model->user->getUserLink();
                }
            ];
            $columns[] = [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.rating.index.th.user.country'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.user.country')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1', 'title' => Yii::t('frontend', 'views.rating.index.title.user.country')],
                'label' => Yii::t('frontend', 'views.rating.index.th.user.country'),
                'value' => static function (RatingUser $model) {
                    return $model->user->country ? $model->user->country->getImage() : '';
                }
            ];
            $columns[] = [
                'attribute' => 'val',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.rating.index.th.rating'),
                'headerOptions' => ['class' => 'col-15'],
                'label' => Yii::t('frontend', 'views.rating.index.th.rating'),
                'value' => static function (RatingUser $model) {
                    return $model->user->rating;
                }
            ];
        } else {
            $columns[] = [
                'footer' => Yii::t('frontend', 'views.rating.index.th.country'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.rating.index.th.country'),
                'value' => static function (RatingFederation $model) {
                    return $model->federation->country->getImageTextLink();
                }
            ];

            if (RatingType::FEDERATION_STADIUM === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.federation.stadium'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.federation.stadium'),
                    'value' => static function (RatingFederation $model) {
                        return $model->federation->stadium_capacity;
                    }
                ];
            } elseif (RatingType::FEDERATION_AUTO === $ratingType->id) {
                $columns[] = [
                    'attribute' => 'game',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.game'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.game')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.game')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.game'),
                    'value' => static function (RatingFederation $model) {
                        return $model->federation->game;
                    }
                ];
                $columns[] = [
                    'attribute' => 'auto',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.auto'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.auto')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.auto')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.auto'),
                    'value' => static function (RatingFederation $model) {
                        return $model->federation->auto;
                    }
                ];
                $columns[] = [
                    'attribute' => 'val',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.rating.index.th.percent'),
                    'headerOptions' => ['class' => 'col-10'],
                    'label' => Yii::t('frontend', 'views.rating.index.th.percent'),
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
                        'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.th.season') . ' ' . $columnSeason],
                        'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.th.season') . ' ' . $columnSeason],
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
                    'footer' => Yii::t('frontend', 'views.rating.index.th.coefficient'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.rating.index.title.coefficient')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.rating.index.title.coefficient')],
                    'label' => Yii::t('frontend', 'views.rating.index.th.coefficient') . 'K',
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
