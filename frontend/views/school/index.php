<?php

// TODO refactor

use common\models\db\School;
use common\models\db\Team;
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
                Спортшкола
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Уровень:
                <span class="strong"><?= $team->baseSchool->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Время подготовки игрока:
                <span class="strong"><?= $team->baseSchool->school_speed ?></span> дней
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Осталось юниоров:
                <span class="strong"><?= $team->availableSchool() ?></span>
                из
                <span class="strong"><?= $team->baseSchool->player_count ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Из них со спецвозможностью:
                <span class="strong"><?= $team->availableSchoolWithSpecial() ?></span>
                из
                <span class="strong"><?= $team->baseSchool->with_special ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Из них со стилем:
                <span class="strong"><?= $team->availableSchoolWithStyle() ?></span>
                из
                <span class="strong"><?= $team->baseSchool->with_style ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Здесь - <span class="strong">в спортшколе</span> -
        вы можете подготовить молодых игроков для основной команды:
    </div>
</div>
<?php if ($schoolArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            Сейчас происходит подготовка юниора:
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Игрок</th>
                    <th class="col-1 hidden-xs" title="Национальность">Нац</th>
                    <th class="col-10" title="Позиция">Поз</th>
                    <th class="col-5" title="Возраст">В</th>
                    <th class="col-15" title="Спецвозможности">Спец</th>
                    <th class="col-15">Стиль</th>
                    <th class="col-15">Осталось дней</th>
                    <th class="col-1"></th>
                </tr>
                <?php foreach ($schoolArray as $item) : ?>
                    <tr>
                        <td>Молодой игрок</td>
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
                                '<i class="fa fa-times-circle"></i>',
                                ['cancel', 'id' => $item->id],
                                ['title' => 'Отменить подготовку игрока']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th>Игрок</th>
                    <th class="hidden-xs" title="Национальность">Нац</th>
                    <th title="Позиция">Поз</th>
                    <th title="Возраст">В</th>
                    <th title="Спецвозможности">Спец</th>
                    <th>Стиль</th>
                    <th>Осталось дней</th>
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
                    <th>Игрок</th>
                    <th class="col-1" title="Национальность">Нац</th>
                    <th class="col-5" title="Возраст">В</th>
                    <th class="col-15" title="Позиция">Поз</th>
                    <th class="col-15" title="Спецвозможности">Спец</th>
                    <th class="col-15">Стиль</th>
                </tr>
                <tr>
                    <td>
                        Молодой игрок
                    </td>
                    <td class="text-center">
                        <?= $team->stadium->city->country->getImageLink() ?>
                    </td>
                    <td class="text-center"><?= School::AGE ?></td>
                    <td class="text-center">
                        <?= Html::dropDownList(
                            'position_id',
                            null,
                            $positionArray,
                            ['class' => 'form-control']
                        ) ?>
                    </td>
                    <td class="text-center">
                        <?= Html::dropDownList(
                            'special_id',
                            null,
                            $specialArray,
                            ['class' => 'form-control', 'prompt' => '-']
                        ) ?>
                    </td>
                    <td class="text-center">
                        <?= Html::dropDownList(
                            'style_id',
                            null,
                            $styleArray,
                            ['class' => 'form-control', 'prompt' => '-']
                        ) ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <input class="btn margin" type="submit" value="Продолжить"/>
        </div>
    </div>
<?php endif ?>
