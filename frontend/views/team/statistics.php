<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use yii\web\View;

/**
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th colspan="2" rowspan="2"><?= Yii::t('frontend', 'views.team.statistics.th.name') ?></th>
                <th class="hidden-xs" colspan="3"><?= Yii::t('frontend', 'views.team.statistics.th.place') ?></th>
            </tr>
            <tr>
                <th class="col-15 hidden-xs"><?= Yii::t('frontend', 'views.team.statistics.th.league') ?></th>
                <th class="col-15 hidden-xs"><?= Yii::t('frontend', 'views.team.statistics.th.country') ?></th>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.visitor') ?>:</td>
                <td class="col-10 text-center">
                    <?= Yii::$app->formatter->asDecimal($team->visitor / 100) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->visitor_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->visitor_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.capacity') ?>:</td>
                <td class="text-center">
                    <?= $team->stadium->capacity ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->stadium_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->stadium_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.age') ?>:</td>
                <td class="text-center">
                    <?= Yii::$app->formatter->asDecimal($team->player_average_age) ?></td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->age_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->age_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.player') ?>:</td>
                <td class="text-center">
                    <?= $team->player_number ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->player_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->player_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.vs') ?>:</td>
                <td class="text-center">
                    <?= $team->power_vs ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->power_vs_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->power_vs_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.base') ?>:</td>
                <td class="text-center">
                    <?= $team->base->level ?> (<?= $team->baseUsed() ?>)
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->base_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->base_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.salary') ?>:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->salary) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->salary_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->salary_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.finance') ?>:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->finance) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->finance_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->finance_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.price.base') ?>:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->price_base) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->price_base_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->price_base_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.price.stadium') ?>:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->price_stadium) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->price_stadium_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->price_stadium_place_federation ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.statistics.price') ?>:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->price_total) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->price_total_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->price_total_place_federation ?? 0 ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
