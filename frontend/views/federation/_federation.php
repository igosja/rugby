<?php

// TODO refactor

use common\models\db\Federation;
use common\models\db\Support;
use frontend\controllers\AbstractController;
use frontend\models\queries\AttitudeQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var Federation $federation
 */

$file_name = 'file_name';

$attitudeArray = AttitudeQuery::getAttitudeList();
$attitudeArray = ArrayHelper::map($attitudeArray, 'id', 'name');

$supportAdmin = Support::find()
    ->andWhere(['federation_id' => $federation->id, 'is_inside' => false, 'is_question' => false, 'read' => null])
    ->count();

$supportPresident = Support::find()
    ->andWhere(['federation_id' => $federation->id, 'is_inside' => true, 'is_question' => true, 'read' => null])
    ->count();

$supportManager = 0;
if (!Yii::$app->user->isGuest) {
    $supportManager = Support::find()
        ->andWhere(['federation_id' => $federation->id, 'is_inside' => true, 'is_question' => false, 'read' => null, 'user_id' => Yii::$app->user->id])
        ->count();
}

/**
 * @var AbstractController $controller
 */
$controller = Yii::$app->controller;

?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1>
            <?= $federation->country->name ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('_federation-links', [
            'id' => $federation->id,
        ]) ?>
    </div>
</div>
<?php if (file_exists(Yii::getAlias('@webroot') . '/img/country/100/' . $federation->country_id . '.png')) : ?>
    <div class="row margin-top">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center team-logo-div">
            <?= Html::img(
                '/img/country/100/' . $federation->country_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/country/100/' . $federation->country_id . '.png'),
                [
                    'alt' => $federation->country->name,
                    'class' => 'country-logo',
                    'title' => $federation->country->name,
                ]
            ) ?>
        </div>
    </div>
<?php endif ?>
