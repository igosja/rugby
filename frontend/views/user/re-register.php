<?php

// TODO refactor

use common\models\db\Team;
use common\models\db\User;
use yii\helpers\Html;

/**
 * @var Team $team
 * @var User $model
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
                <th><?= Yii::t('frontend', 'views.user.re-register.title') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.user.re-register.text') ?>
        <?= $team->getTeamImageLink() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <?= Html::a(Yii::t('frontend', 'views.user.re-register.sink.ok'), ['re-register', 'ok' => true], ['class' => 'btn margin']) ?>
            <?= Html::a(Yii::t('frontend', 'views.user.re-register.link.view'), ['view'], ['class' => 'btn margin']) ?>
        </p>
    </div>
</div>