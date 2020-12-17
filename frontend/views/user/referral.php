<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;

/**
 * @var ActiveDataProvider $dataProvider
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
                <th>Ваши подопечные</th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-justify">
            <span class="strong">Вместе играть веселее.</span>
            Чем больше менеджеров играют в Виртуальной Хоккейной Лиге, тем интереснее и разнообразнее становится игра.
            Мы постоянно тратим огромное количество усилий и средств на то, чтобы рассказать
            как можно большему числу любителей хоккея о нашей игре - это долго, дорого и не всегда эффективно.
        </p>
        <p class="text-justify">
            Поэтому мы предлагам вам - нашим менеджерам:
            <span class="strong">приглашайте в игру новых участников.</span>
            Мы лучше выплатим вознаграждение вам, чем кому-то другому.
            Вы можете рассказать о нашей игре своим друзьям, знакомым, сотрудникам, одноклассникам и одногруппникам,
            посетителям вашего блога или сайта, на своем любимом форуме или чате, в социальной сети.
        </p>
        <p class="text-justify">
            Публикуйте статьи, обзоры, сообщения, объявления, рекламные баннеры, создайте тему на игровом форуме -
            всё то, что вам доступно, только не пользуйтесь запрещёнными законодательством методами (например, спам).
        </p>
        <p class="text-justify">
            Используйте для приглашения <span class="strong">вашу личную ссылку</span> на наш сайт - вот она:
        </p>
        <p class="text-center text-size-1 strong alert info">
            <?= Url::to(['site/index', 'ref' => Yii::$app->user->id], true) ?>
        </p>
        <p class="text-justify">
            Все, кто зайдет на сайт по этой ссылке и зарегистрируется в игре,
            автоматически <span class="strong">станут вашими подопечными</span>.
        </p>
        <p class="text-justify">
            На ваш денежный счёт будут всегда зачисляться <span class="strong">10%</span> от всех единиц,
            купленных этими игроками!
        </p>
        <p class="text-justify">
            А в случае, если ваш подопечный сможет разобраться в игре и стать настоящим менеджером
            Виртуальной Хоккейной Лиги - вас ждет <span class="strong">дополнительное вознаграждение</span> в виде
            <span class="strong"><?= FormatHelper::asCurrency(1000000) ?></span> на личный счет менеджера.
        </p>
        <p class="text-justify">
            Условия получения <span class="strong">дополнительного вознаграждения</span>:
        </p>
        <ul>
            <li>Ваш подопечный смог на протяжении 30 дней управлять полученной командой.</li>
            <li>Вы не играли с подопечным на одном компьютере и ваши подопечные тоже между собой не пересекались.</li>
            <li>Cкаут-коллегия не считает, что ваш подопечный может являться подставным аккаунтом.</li>
        </ul>
        <p class="text-justify strong red">
            Внимание!
        </p>
        <ul class="red">
            <li>Запрещено приглашать подопечных способами, которые нарушают законы (например, рассылать спам).</li>
            <li>Запрещено просить кого-либо перерегистрироваться на сайте, указав вас старшим менеджером.</li>
        </ul>
        <p class="text-justify">
            Любые средства на личном счете менеджера (включая вознаграждения за подопечных) являются игровыми
            и могут быть потрачены только для покупки игровых товаров на нашем сайте или подарков другим менеджерам.
        </p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'footer' => 'Менеджер',
                'format' => 'raw',
                'label' => 'Менеджер',
                'value' => static function (User $model) {
                    return $model->getUserLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'footer' => 'Последний визит',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => 'Последний визит',
                'headerOptions' => ['class' => 'col-25 hidden-xs'],
                'value' => static function (User $model) {
                    return $model->lastVisit();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'footer' => 'Дата регистрации',
                'footerOptions' => ['class' => 'hidden-xs'],
                'label' => 'Дата регистрации',
                'headerOptions' => ['class' => 'col-25 hidden-xs'],
                'value' => static function (User $model) {
                    return FormatHelper::asDateTime($model->date_register);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
