<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use common\models\db\Support;
use frontend\components\AbstractController;
use frontend\models\queries\AttitudeQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Federation $federation
 */

$file_name = 'file_name';

$attitudeArray = AttitudeQuery::getAttitudeList();
$attitudeArray = ArrayHelper::map($attitudeArray, 'attitude_id', 'attitude_name');

$supportAdmin = Support::find()
    ->where(['support_country_id' => $federation->federation_country_id, 'support_inside' => 0, 'support_question' => 0, 'support_read' => 0])
    ->count();

$supportPresident = Support::find()
    ->where(['support_country_id' => $federation->federation_country_id, 'support_inside' => 1, 'support_question' => 1, 'support_read' => 0])
    ->count();

$supportManager = 0;
if (!Yii::$app->user->isGuest) {
    $supportManager = Support::find()
        ->where(['support_country_id' => $federation->federation_country_id, 'support_inside' => 1, 'support_question' => 0, 'support_read' => 0, 'support_user_id' => Yii::$app->user->id])
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
            <?= $federation->country->country_name ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('_federation-links', [
            'countryId' => $federation->federation_country_id,
        ]) ?>
    </div>
</div>
<?php

// TODO refactor if ('country_national' == $file_name) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= $this->render('_country-national-links') ?>
        </div>
    </div>
<?php

// TODO refactor endif ?>
<div class="row margin-top">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center team-logo-div">
        <?php

// TODO refactor if (file_exists(Yii::getAlias('@webroot') . '/img/country/100/' . $federation->federation_country_id . '.png')) : ?>
            <?= Html::img(
                '/img/country/100/' . $federation->federation_country_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/country/100/' . $federation->federation_country_id . '.png'),
                [
                    'alt' => $federation->country->country_name,
                    'class' => 'country-logo',
                    'title' => $federation->country->country_name,
                ]
            ) ?>
        <?php

// TODO refactor endif ?>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Президент:
                <?php

// TODO refactor if ($federation->federation_president_id) : ?>
                    <?= $federation->president->userLink(['class' => 'strong']) ?>
                <?php

// TODO refactor else : ?>
                    -
                <?php

// TODO refactor endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Последний визит:
                <?= $federation->president->lastVisit() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Рейтинг президента:
                <span class="font-green strong"><?= $federation->attitudePresidentPositive() ?>%</span>
                |
                <span class="font-yellow strong"><?= $federation->attitudePresidentNeutral() ?>%</span>
                |
                <span class="font-red strong"><?= $federation->attitudePresidentNegative() ?>%</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Заместитель президента:
                <?php

// TODO refactor if ($federation->federation_vice_id) : ?>
                    <?= $federation->vice->userLink(['class' => 'strong']) ?>
                <?php

// TODO refactor else : ?>
                    -
                <?php

// TODO refactor endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Последний визит:
                <?= $federation->vice->lastVisit() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Фонд федерации:
                <span class="strong">
                        <?= FormatHelper::asCurrency($federation->federation_finance) ?>
                    </span>
            </div>
        </div>
    </div>
</div>
<?php

// TODO refactor if ($controller->myTeam && $controller->myTeam->stadium->city->city_country_id === $federation->federation_country_id) : ?>
    <?php

// TODO refactor $form = ActiveForm::begin([
        'action' => ['country/attitude-president', 'id' => $federation->federation_country_id],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'strong'],
            'options' => ['class' => 'row text-left'],
            'template' => '<div class="col-lg-3 col-md-3 col-sm-2"></div>{input}',
        ],
    ]) ?>
    <div class="row text-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 relation-head">
            Ваше отношение к президенту федерации:
            <a href="javascript:" id="relation-link"><?= $controller->myTeam->attitudePresident->attitude_name ?></a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 relation-body hidden">
            <?= $form
                ->field($controller->myTeam, 'team_attitude_president')
                ->radioList($attitudeArray, [
                    'item' => function ($index, $model, $name, $checked, $value) {
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
                        'Изменить отношение',
                        ['class' => 'btn margin']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
    <?php

// TODO refactor ActiveForm::end() ?>
    <div class="row margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            <?= Html::a(
                'Общение с президентом федерации'
                . ($supportManager ? '<sup class="text-size-4">' . $supportManager . '</sup>' : ''),
                ['country/support-manager', 'id' => $federation->federation_country_id],
                ['class' => ($supportManager ? 'red' : '')]
            ) ?>
        </div>
    </div>
<?php

// TODO refactor endif ?>
<?php

// TODO refactor if (!Yii::$app->user->isGuest && in_array(Yii::$app->user->id, [$federation->federation_president_id, $federation->federation_vice_id], true)) : ?>
    <div class="row margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            <?= Html::a(
                'Создать новость',
                ['country/news-create', 'id' => $federation->federation_country_id]
            ) ?>
            |
            <?= Html::a(
                'Создать опрос',
                ['country/poll-create', 'id' => $federation->federation_country_id]
            ) ?>
            |
            <?= Html::a(
                'Общение с тех.поддержкой'
                . ($supportAdmin ? '<sup class="text-size-4">' . $supportAdmin . '</sup>' : ''),
                ['country/support-admin', 'id' => $federation->federation_country_id],
                ['class' => ($supportAdmin ? 'red' : '')]
            ) ?>
            |
            <?= Html::a(
                'Общение с менеджерами'
                . ($supportPresident ? '<sup class="text-size-4">' . $supportPresident . '</sup>' : ''),
                ['country/support-president', 'id' => $federation->federation_country_id],
                ['class' => ($supportPresident ? 'red' : '')]
            ) ?>
            |
            <?= Html::a(
                'Свободные команды',
                ['country/free-team', 'id' => $federation->federation_country_id]
            ) ?>
            <?php

// TODO refactor if (Yii::$app->user->id === $federation->federation_president_id): ?>
                |
                <?= Html::a(
                    'Распределить фонд',
                    ['country/money-transfer', 'id' => $federation->federation_country_id]
                ) ?>
            <?php

// TODO refactor endif ?>
            <?php

// TODO refactor if ((Yii::$app->user->id === $federation->federation_president_id && $federation->federation_vice_id) || Yii::$app->user->id === $federation->federation_vice_id) : ?>
                |
                <?= Html::a(
                    'Отказаться от должности',
                    ['country/fire', 'id' => $federation->federation_country_id]
                ) ?>
            <?php

// TODO refactor endif ?>
        </div>
    </div>
<?php

// TODO refactor endif ?>
