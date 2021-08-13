<?php

// TODO refactor

use common\models\db\Game;
use yii\helpers\Html;

/**
 * @var Game $game
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    Name (per game)
                </th>
                <th class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    Site result
                </th>
                <th>
                    Real
                </th>
            </tr>
            <tr>
                <td>
                    Points
                </td>
                <td>
                    <?= round($game['home_point'], 3) ?>
                </td>
                <td>
                    24
                </td>
            </tr>
            <tr>
                <td>
                    Tries
                </td>
                <td>
                    <?= round($game['home_try'], 3) ?>
                </td>
                <td>
                    2.8
                </td>
            </tr>
            <tr>
                <td>
                    Penalty kicks
                </td>
                <td>
                    <?= round($game['home_penalty_kick'], 3) ?>
                </td>
                <td>
                    2.2
                </td>
            </tr>
            <tr>
                <td>
                    Drop goals
                </td>
                <td>
                    <?= round($game['home_drop_goal'], 5) ?>
                </td>
                <td>
                    0.005
                </td>
            </tr>
            <tr>
                <td>
                    Conversions
                </td>
                <td>
                    <?= round($game['home_conversion'], 3) ?>
                </td>
                <td>
                    1.68
                </td>
            </tr>
            <tr>
                <td>
                    Yellow cards
                </td>
                <td>
                    <?= round($game['home_yellow_card'], 3) ?>
                </td>
                <td>
                    0.4
                </td>
            </tr>
            <tr>
                <td>
                    Red cards
                </td>
                <td>
                    <?= round($game['home_red_card'], 3) ?>
                </td>
                <td>
                    0.02
                </td>
            </tr>
            <tr>
                <td>
                    Conversion, %
                </td>
                <td>
                    <?= round($game['home_conversion'] / ($game['home_try'] ?: 1) * 100, 3) ?>%
                </td>
                <td>
                    60%
                </td>
            </tr>
        </table>
    </div>
</div>
