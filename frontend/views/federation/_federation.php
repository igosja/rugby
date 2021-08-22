<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use common\models\db\Support;
use frontend\controllers\AbstractController;
use frontend\models\queries\AttitudeQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

$file_name = 'file_name';

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
<?php if ('country_national' === $file_name) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= $this->render('_country-national-links') ?>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center team-logo-div">
        <?php if (file_exists(Yii::getAlias('@webroot') . '/img/country/100/' . $federation->country_id . '.png')) : ?>
            <?= Html::img(
                '/img/country/100/' . $federation->country_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/country/100/' . $federation->country_id . '.png'),
                [
                    'alt' => $federation->country->name,
                    'class' => 'country-logo',
                    'title' => $federation->country->name,
                ]
            ) ?>
        <?php endif ?>
    </div>
</div>
<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'views.federation.federation.president') ?>
            <?php if ($federation->president_user_id) : ?>
                <?= $federation->presidentUser->getUserLink(['class' => 'strong']) ?>
            <?php else : ?>
                -
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'views.federation.federation.last-visit') ?>
            <?= $federation->presidentUser->lastVisit() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'views.federation.federation.rating') ?>
            <span class="font-green strong"><?= $federation->attitudePresidentPositive() ?>%</span>
            |
            <span class="font-yellow strong"><?= $federation->attitudePresidentNeutral() ?>%</span>
            |
            <span class="font-red strong"><?= $federation->attitudePresidentNegative() ?>%</span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'views.federation.federation.vice') ?>
            <?php if ($federation->vice_user_id) : ?>
                <?= $federation->viceUser->getUserLink(['class' => 'strong']) ?>
            <?php else : ?>
                -
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'views.federation.federation.last-visit') ?>
            <?= $federation->viceUser->lastVisit() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'views.federation.federation.fund') ?>
            <span class="strong">
                <?= FormatHelper::asCurrency($federation->finance) ?>
            </span>
        </div>
    </div>
</div>
<?php if ($controller->myTeam && $controller->myTeam->stadium->city->country_id === $federation->country_id) : ?>
    <?php if ($federation->president_user_id && $federation->president_user_id !== $controller->user->id) : ?>
        <?php $form = ActiveForm::begin([
            'action' => ['federation/attitude-president', 'id' => $federation->id],
            'fieldConfig' => [
                'labelOptions' => ['class' => 'strong'],
                'options' => ['class' => 'row text-left'],
                'template' => '<div class="col-lg-3 col-md-3 col-sm-2"></div>{input}',
            ],
        ]) ?>
        <div class="row text-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 relation-head">
                <?= Yii::t('frontend', 'views.federation.federation.attitude') ?>
                <a href="javascript:" id="relation-link"><?= $controller->myTeam->presidentAttitude->name ?></a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 relation-body hidden">
                <?= $form
                    ->field($controller->myTeam, 'president_attitude_id')
                    ->radioList($attitudeArray, [
                        'item' => static function ($index, $model, $name, $checked, $value) {
                            return '<div class="hidden-lg hidden-md hidden-sm col-xs-3"></div><div class="col-lg-2 col-md-2 col-sm-3 col-xs-9">'
                                . Html::radio($name, $checked, [
                                    'index' => $index,
                                    'label' => $model,
                                    'value' => $value,
                                ])
                                . '</div>';
                        }
                    ])
                    ->label(false) ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?= Html::submitButton(
                            Yii::t('frontend', 'views.federation.federation.submit'),
                            ['class' => 'btn margin']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    <?php endif ?>
    <div class="row margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            <?= Html::a(
                Yii::t('frontend', 'views.federation.federation.link.support-manager')
                . ($supportManager ? '<sup class="text-size-4">' . $supportManager . '</sup>' : ''),
                ['federation/support-manager', 'id' => $federation->id],
                ['class' => ($supportManager ? 'red' : '')]
            ) ?>
        </div>
    </div>
<?php endif ?>
<?php if (!Yii::$app->user->isGuest && in_array(Yii::$app->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) : ?>
    <div class="row margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            <?= Html::a(
                Yii::t('frontend', 'views.federation.federation.link.news-create'),
                ['federation/news-create', 'id' => $federation->id]
            ) ?>
            |
            <?= Html::a(
                Yii::t('frontend', 'views.federation.federation.link.vote-create'),
                ['federation/vote-create', 'id' => $federation->id]
            ) ?>
            |
            <?= Html::a(
                Yii::t('frontend', 'views.federation.federation.link.support-admin')
                . ($supportAdmin ? '<sup class="text-size-4">' . $supportAdmin . '</sup>' : ''),
                ['federation/support-admin', 'id' => $federation->id],
                ['class' => ($supportAdmin ? 'red' : '')]
            ) ?>
            |
            <?= Html::a(
                Yii::t('frontend', 'views.federation.federation.link.support-president')
                . ($supportPresident ? '<sup class="text-size-4">' . $supportPresident . '</sup>' : ''),
                ['federation/support-president', 'id' => $federation->id],
                ['class' => ($supportPresident ? 'red' : '')]
            ) ?>
            |
            <?= Html::a(
                Yii::t('frontend', 'views.federation.federation.link.free-team'),
                ['federation/free-team', 'id' => $federation->id]
            ) ?>
            <?php if (Yii::$app->user->id === $federation->president_user_id): ?>
                |
                <?= Html::a(
                    Yii::t('frontend', 'views.federation.federation.link.money-transfer'),
                    ['federation/money-transfer', 'id' => $federation->id]
                ) ?>
            <?php endif ?>
            <?php if ((Yii::$app->user->id === $federation->president_user_id && $federation->vice_user_id) || Yii::$app->user->id === $federation->vice_user_id) : ?>
                |
                <?= Html::a(
                    Yii::t('frontend', 'views.federation.federation.link.fire'),
                    ['federation/fire', 'id' => $federation->id]
                ) ?>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
