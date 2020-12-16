<?php

// TODO refactor

use common\models\db\Team;
use common\models\db\User;
use yii\helpers\Html;

/**
 * @var User $model
 * @var Team $team
 */

print $this->render('//user/_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th>Отказ от управления командой</th>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Вы собираетесь отказаться от управления своей командой
        <?= $team->getTeamImageLink() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <?= Html::a('Отказаться от команды', ['drop-team', 'ok' => true], ['class' => 'btn margin']) ?>
            <?= Html::a('Вернуться', ['view'], ['class' => 'btn margin']) ?>
        </p>
    </div>
</div>