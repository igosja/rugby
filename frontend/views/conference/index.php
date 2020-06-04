<?php

use yii\helpers\Html;

/**
 * @var int $count
 * @var array $seasonArray
 * @var int $seasonId
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>
            Конференция любительских клубов
        </h1>
    </div>
</div>
<?= Html::beginForm('', 'get') ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label('Сезон', 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'seasonId',
            $seasonId,
            $seasonArray,
            ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p class="text-justify">
            Турнир проводится среди новых команд и команд, не попавших в чемпионаты своих стран.
            <br/>
            Новые и вылетевшие в конференцию команды автоматически становятся участниками турнира,
            выйти из числа участников невозможно, в случае неотправки составов заявленная команда играет на
            автосоставах,
            определяемых компьютером.
            Всего команд в Конференции в этом сезоне - <span class="strong"><?= $count ?></span>.
        </p>
        <p class="text-center">
            <?= Html::a('Турнирная таблица', ['table', 'seasonId' => $seasonId]) ?>
            |
            <?= Html::a('Статистика', ['statistics', 'seasonId' => $seasonId]) ?>
        </p>
        <p class="text-justify">
            Турнир играется по швейцарской системе, когда для каждого тура сводятся в пары команды одного ранга
            (расположенные достаточно близко друг от друга в турнирной таблице,
            но так, чтобы не нарушались принципы турнира).
        </p>
        <p class="text-justify">
            В матчах турнира есть домашний бонус - в родных стенах команды играют сильнее.
        </p>
        <p class="text-justify">
            Каждая команда имеет право сыграть 3 матча на супере и 3 матча на отдыхе
            во время розыгрыша кубка межсезонья.
        </p>
        <p class="text-justify">
            В конференции участники не могут встречаться между собой более двух раз и сводятся в пары,
            имеющие ближайшие места в турнирной таблице, но такие,
            которые могут играть между собой в соответствии с принципами жеребьёвки:
        </p>
        <ul class="text-left">
            <li>две команды не могут играть между собой более двух матчей;</li>
            <li>ни одна из команд не может сыграть более половины матчей турнира дома или в гостях.</li>
        </ul>
    </div>
</div>
