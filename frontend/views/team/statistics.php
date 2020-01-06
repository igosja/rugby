<?php

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
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th colspan="2" rowspan="2">Статистический показатель</th>
                <th class="hidden-xs" colspan="3">Место</th>
            </tr>
            <tr>
                <th class="col-15 hidden-xs">в лиге</th>
                <th class="col-15 hidden-xs">в стране</th>
            </tr>
            <tr>
                <td>Рейтинг посещаемости:</td>
                <td class="col-10 text-center">
                    <?= Yii::$app->formatter->asDecimal($team->team_visitor / 100) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_visitor_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_visitor_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Вместимость стадиона:</td>
                <td class="text-center">
                    <?= $team->stadium->stadium_capacity ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_stadium_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_stadium_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Средн. возраст игроков:</td>
                <td class="text-center">
                    <?= Yii::$app->formatter->asDecimal($team->team_age) ?></td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_age_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_age_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Количество игроков:</td>
                <td class="text-center">
                    <?= $team->team_player ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_player_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_player_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Средн. сила состава с учетом спецвозможностей (Vs):</td>
                <td class="text-center">
                    <?= $team->team_power_vs ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_power_vs_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_power_vs_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>База:</td>
                <td class="text-center">
                    <?= $team->base->base_level ?> (<?= $team->baseUsed() ?>)
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_base_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_base_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Зарплата игроков:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->team_salary) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_salary_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_salary_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Денег в кассе:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->team_finance) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_finance_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_finance_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Стоимость базы:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->team_price_base) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_price_base_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_price_base_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Стоимость стадиона:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->team_price_stadium) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_price_stadium_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_price_stadium_place_country ?? 0 ?>
                </td>
            </tr>
            <tr>
                <td>Общая стоимость команды:</td>
                <td class="text-center">
                    <?= FormatHelper::asCurrency($team->team_price_total) ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_price_total_place ?? 0 ?>
                </td>
                <td class="hidden-xs text-center">
                    <?= $team->ratingTeam->rating_team_price_total_place_country ?? 0 ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
