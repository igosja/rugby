<?php

// TODO refactor

use common\models\db\National;
use common\models\db\Player;
use common\models\db\Position;
use frontend\models\forms\NationalPlayer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Player[] $propArray ,
 * @var Player[] $hookerArray ,
 * @var Player[] $lockArray ,
 * @var Player[] $flankerArray ,
 * @var Player[] $eightArray ,
 * @var Player[] $scrumHalfArray ,
 * @var Player[] $flyHalfArray ,
 * @var Player[] $wingArray ,
 * @var Player[] $centreArray ,
 * @var Player[] $fullBackArray ,
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
        <p>В сборной должно быть 30 игрока - 2 игрока на непарные позиции и 4 на парные позиции.</p>
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
                    <th>Игрок</th>
                    <th class="col-5" title="Позиция">Поз</th>
                    <th class="col-5 hidden-xs" title="Возраст">В</th>
                    <th class="col-5" title="Номинальная сила">С</th>
                    <th class="col-5" title="Усталость">У</th>
                    <th class="col-5" title="Форма">Ф</th>
                    <th class="col-10 hidden-xs" title="Спецвозможности">Спец</th>
                    <th class="col-40 hidden-xs">Команда</th>
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
                    <th>Игрок</th>
                    <th title="Позиция">Поз</th>
                    <th class="hidden-xs" title="Возраст">В</th>
                    <th title="Номинальная сила">С</th>
                    <th title="Усталость">У</th>
                    <th title="Форма">Ф</th>
                    <th class="hidden-xs" title="Спецвозможности">Спец</th>
                    <th class="hidden-xs">Команда</th>
                </tr>
            </table>
        </div>
    </div>
<?php endfor ?>
<?= $this->render('//site/_show-full-table') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
