<?php

// TODO refactor

/**
 * @var National $national
 */

use common\components\helpers\FormatHelper;
use common\models\db\National;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-1 strong">
                <?= $national->fullName() ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                Дивизион: <?= $national->division() ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Менеджер:
                <?php if ($national->user->canDialog()) : ?>
                    <?= Html::a(
                        FAS::icon(FAS::_ENVELOPE),
                        ['messenger/view', 'id' => $national->user->id]
                    ) ?>
                <?php endif ?>
                <?= Html::a(
                    $national->user->fullName(),
                    ['user/view', 'id' => $national->user->id],
                    ['class' => 'strong']
                ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Ник:
                <?= $national->user->iconVip() ?>
                <?= $national->user->getUserLink(['class' => 'strong']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Рейтинг тренера:
                <span class="font-green strong"><?= $national->attitudeNationalPositive() ?>%</span>
                |
                <span class="font-yellow strong"><?= $national->attitudeNationalNeutral() ?>%</span>
                |
                <span class="font-red strong"><?= $national->attitudeNationalNegative() ?>%</span>
            </div>
        </div>
        <?php if ($national->vice_user_id) : ?>
            <div class="row margin-top-small">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Заместитель:
                    <?php if ($national->viceUser->canDialog()) : ?>
                        <?= Html::a(
                            FAS::icon(FAS::_ENVELOPE),
                            ['messenger/view', 'id' => $national->viceUser->id]
                        ) ?>
                    <?php endif ?>
                    <?= Html::a(
                        $national->viceUser->fullName(),
                        ['user/view', 'id' => $national->viceUser->id],
                        ['class' => 'strong']
                    ) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Ник:
                    <?= $national->viceUser->iconVip() ?>
                    <?= $national->viceUser->getUserLink(['class' => 'strong']) ?>
                </div>
            </div>
        <?php endif ?>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Стадион:
                <?= $national->stadium->name ?>,
                <strong><?= Yii::$app->formatter->asInteger($national->stadium->capacity) ?></strong>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Финансы:
                <span class="strong">
                    <?= FormatHelper::asCurrency($national->finance) ?>
                </span>
            </div>
        </div>
    </div>
</div>