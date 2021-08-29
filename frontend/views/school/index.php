<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\School;
use common\models\db\Team;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var bool $onBuilding
 * @var array $positionArray
 * @var School[] $schoolArray
 * @var array $specialArray
 * @var array $styleArray
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                <?= Yii::t('frontend', 'views.school.title') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.school.level') ?>:
                <span class="strong"><?= $team->baseSchool->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.school.speed', ['speed' => $team->baseSchool->school_speed]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.school.available', [
                    'available' => $team->availableSchool(),
                    'count' => $team->baseSchool->player_count,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.school.with-special', [
                    'available' => $team->availableSchoolWithSpecial(),
                    'count' => $team->baseSchool->with_special,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.school.with-style', [
                    'available' => $team->availableSchoolWithStyle(),
                    'count' => $team->baseSchool->with_style,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.school.p') ?>
    </div>
</div>
<?php if ($schoolArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= Yii::t('frontend', 'views.school.index.now') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th class="col-1 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.th.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-15"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="col-15"><?= Yii::t('frontend', 'views.th.style') ?></th>
                    <th class="col-15"><?= Yii::t('frontend', 'views.school.th.day') ?></th>
                    <th class="col-1"></th>
                </tr>
                <?php foreach ($schoolArray as $item) : ?>
                    <tr>
                        <td><?= Yii::t('frontend', 'views.school.td.player') ?></td>
                        <td class="hidden-xs text-center">
                            <?= $team->stadium->city->country->getImageLink() ?>
                        </td>
                        <td class="text-center"><?= $item->position->name ?></td>
                        <td class="text-center"><?= School::AGE ?></td>
                        <td class="text-center">
                            <?= $item->is_with_special ? $item->special->name : '-' ?>
                        </td>
                        <td class="text-center"><?= $item->is_with_style ? $item->style->name : '?' ?></td>
                        <td class="text-center"><?= $item->day ?></td>
                        <td class="text-center">
                            <?= Html::a(
                                FAR::icon(FontAwesome::_TIMES_CIRCLE),
                                ['cancel', 'id' => $item->id],
                                ['title' => Yii::t('frontend', 'views.school.link.cancel')]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th class="hidden-xs"
                        title="<?= Yii::t('frontend', 'views.th.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th><?= Yii::t('frontend', 'views.th.style') ?></th>
                    <th><?= Yii::t('frontend', 'views.school.th.day') ?></th>
                    <th></th>
                </tr>
            </table>
        </div>
    </div>
<?php else : ?>
    <?= Html::beginForm(['index']) ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th class="col-1"
                        title="<?= Yii::t('frontend', 'views.th.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-15"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="col-15"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="col-15"><?= Yii::t('frontend', 'views.th.style') ?></th>
                </tr>
                <tr>
                    <td>
                        <?= Yii::t('frontend', 'views.school.td.player') ?>
                    </td>
                    <td class="text-center">
                        <?= $team->stadium->city->country->getImageLink() ?>
                    </td>
                    <td class="text-center"><?= School::AGE ?></td>
                    <td class="text-center">
                        <?php

                        try {
                            print Select2::widget([
                                'data' => $positionArray,
                                'name' => 'position_id',
                            ]);
                        } catch (Exception $e) {
                            ErrorHelper::log($e);
                        }

                        ?>
                    </td>
                    <td class="text-center">
                        <?php

                        try {
                            print Select2::widget([
                                'data' => $specialArray,
                                'name' => 'special_id',
                                'options' => ['prompt' => '-'],
                            ]);
                        } catch (Exception $e) {
                            ErrorHelper::log($e);
                        }

                        ?>
                    </td>
                    <td class="text-center">
                        <?php

                        try {
                            print Select2::widget([
                                'data' => $styleArray,
                                'name' => 'style_id',
                                'options' => ['prompt' => '-'],
                            ]);
                        } catch (Exception $e) {
                            ErrorHelper::log($e);
                        }

                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.school.submit'), ['class' => 'btn margin']) ?>
        </div>
    </div>
    <?= Html::endForm() ?>
<?php endif ?>
