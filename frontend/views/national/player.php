<?php

// TODO refactor

use common\models\db\National;
use common\models\db\Player;
use common\models\db\Position;
use frontend\models\forms\NationalPlayer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Player[] $propArray
 * @var Player[] $hookerArray
 * @var Player[] $lockArray
 * @var Player[] $flankerArray
 * @var Player[] $eightArray
 * @var Player[] $scrumHalfArray
 * @var Player[] $flyHalfArray
 * @var Player[] $wingArray
 * @var Player[] $centreArray
 * @var Player[] $fullBackArray
 * @var NationalPlayer $model
 * @var National $national
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//national/_national-top-left', ['national' => $national]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//national/_national-top-right', ['national' => $national]) ?>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notification-error'],
        'options' => ['class' => 'row'],
        'template' => '{error}',
    ],
]) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p><?= Yii::t('frontend', 'views.national.player.p') ?></p>
    </div>
</div>
<?= $form->field($model, 'player[]')->error() ?>
<?php for ($i = Position::PROP; $i <= Position::FULL_BACK; $i++) : ?>
    <?php

    if (Position::PROP === $i) {
        $playerArray = $propArray;
    } elseif (Position::HOOKER === $i) {
        $playerArray = $hookerArray;
    } elseif (Position::LOCK === $i) {
        $playerArray = $lockArray;
    } elseif (Position::FLANKER === $i) {
        $playerArray = $flankerArray;
    } elseif (Position::EIGHT === $i) {
        $playerArray = $eightArray;
    } elseif (Position::SCRUM_HALF === $i) {
        $playerArray = $scrumHalfArray;
    } elseif (Position::FLY_HALF === $i) {
        $playerArray = $flyHalfArray;
    } elseif (Position::WING === $i) {
        $playerArray = $wingArray;
    } elseif (Position::CENTRE === $i) {
        $playerArray = $centreArray;
    } else {
        $playerArray = $fullBackArray;
    }

    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th class="col-5"></th>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="col-5 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.th.nominal-power') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.tire') ?>"><?= Yii::t('frontend', 'views.th.tire') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.physical') ?>"><?= Yii::t('frontend', 'views.th.physical') ?></th>
                    <th class="col-10 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="col-40 hidden-xs"><?= Yii::t('frontend', 'views.th.team') ?></th>
                </tr>
                <?php foreach ($playerArray as $item) : ?>
                    <tr>
                        <td class="text-center">
                            <?= Html::checkbox(
                                'NationalPlayer[player][' . $i . '][]',
                                in_array($item->id, $model->playerArray, true),
                                [
                                    'value' => $item->id,
                                ]
                            ) ?>
                        </td>
                        <td>
                            <?= $item->getPlayerLink(['target' => '_blank']) ?>
                        </td>
                        <td class="text-center"><?= $item->position() ?></td>
                        <td class="text-center hidden-xs"><?= $item->age ?></td>
                        <td class="text-center"><?= $item->power_nominal ?></td>
                        <td class="text-center"><?= $item->tire ?></td>
                        <td class="text-center"><?= $item->physical->image() ?></td>
                        <td class="hidden-xs text-center"><?= $item->special() ?></td>
                        <td class="hidden-xs"><?= $item->team->getTeamImageLink() ?></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th></th>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.th.nominal-power') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.tire') ?>"><?= Yii::t('frontend', 'views.th.tire') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.physical') ?>"><?= Yii::t('frontend', 'views.th.physical') ?></th>
                    <th class="hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="hidden-xs"><?= Yii::t('frontend', 'views.th.team') ?></th>
                </tr>
            </table>
        </div>
    </div>
<?php endfor ?>
<?= $this->render('//site/_show-full-table') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.national.player.submit'), ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
