<?php

use common\models\db\ForumMessage;
use common\models\db\News;
use common\models\db\User;
use yii\helpers\Html;

/**
 * @var User[] $birthdayBoys
 * @var ForumMessage[] $forumMessage
 * @var News $news
 */

?>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1>Онлайн-менеджер для истинных любителей регби!</h1>
                <p class="text-justify">
                    Наверняка каждый из нас мечтал почувствовать себя тренером или менеджером
                    настоящего регбийного клуба. Увлекательный поиск талантливых игроков,
                    постепенное развитие инфраструктуры, выбор подходящей тактики на игру, регулярные матчи и,
                    конечно же, победы, титулы и новые достижения! Именно это ждёт Вас в нашем мире виртуального регби.
                    Окунитесь в него и создайте клуб своей мечты!
                </p>
                <h4>Играть в наш регбийный онлайн-менеджер может каждый!</h4>
                <p class="text-justify">
                    Наш проект открыт для всех! Чтобы начать играть, Вам достаточно просто пройти
                    элементарную процедуру регистрации. <strong>"Виртуальная Регбийная Лига"</strong>
                    - это функциональный регбийный онлайн-менеджер,
                    в котором Вы получите возможность пройти увлекательный путь развития своей команды
                    от низших дивизионов до побед в национальных чемпионатах и мировых кубках!
                </p>
                <?php
                if (Yii::$app->user->isGuest) : ?>
                    <p class="text-center">
                        <?= Html::a(
                            'Зарегистрироваться',
                            ['sign-up'],
                            ['class' => 'btn']
                        ) ?>
                    </p>
                <?php
                endif ?>
                <h4>Скачивать ничего не надо!</h4>
                <p class="text-justify">
                    Обращаем внимание, что наш регбийный онлайн-менеджер является браузерной игрой.
                    Поэтому Вам не надо будет скачивать какие-либо клиентские программы,
                    тратить время на их утомительную установку и последующую настройку.
                    Для игры Вам необходим только доступ к интернету и несколько минут свободного времени.
                    При этом участие в турнирах является <strong>абсолютно бесплатным</strong>.
                </p>
                <h4 class="center header">Вы обязательно станете чемпионом!</h4>
                <p class="text-justify">
                    Хотим подчеркнуть, что для достижения успеха Вам не надо целыми сутками сидеть за компьютером.
                    Чтобы постепенно развивать свой клуб, участвовать в трансферах и играть календарные матчи,
                    Вам достаточно иметь возможность хотя бы несколько раз в неделю посещать наш сайт.
                </p>
                <h4 class="center header">Увлекательные регбийные матчи и первые победы уже ждут Вас!</h4>
                <p class="text-justify">
                    Регбийный онлайн-менеджер <strong>"Виртуальная Регбийная Лига"</strong> - это больше,
                    чем обычная игра. Это сообщество людей, которые объединены страстью и любовью к регби.
                    Здесь Вы обязательно сможете найти интересных людей, заведете новые знакомства и просто отлично
                    проведетё время в непринужденной и максимально комфортной атмосфере.
                    Вперёд, пришло время занять тренерское кресло и кабинет менеджера!
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2>Последние игровые новости</h2>
            </div>
        </div>
        <?php if ($news) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-justify">
                        <span class="strong"><?= $news->title ?></span>
                    </p>
                    <p class="text-justify">
                        <?= $news->text ?>
                    </p>
                    <?= Html::a(
                        Html::encode($news->user->login),
                        ['user/view', 'id' => $news->id]
                    ) ?>
                    <p class="text-justify text-size-3">
                        [<?= Html::a('Подробнее', ['news/index']) ?>]
                    </p>
                </div>
            </div>
        <?php
        endif ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2>Как стать менеджером регбийной команды?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-justify">Для того, чтобы стать участником игры, вам нужно:</p>
                <ul>
                    <li>
                        <?= Html::a(
                            'зарегистрироваться в игре',
                            ['site/sign-up'],
                            ['class' => 'strong']
                        ) ?>, получить письмо с кодом подтверждения регистрации;
                    </li>
                    <li>
                        активировать свою регистрацию с помощью кода, полученного в письме, на <?= Html::a(
                            'этой странице',
                            ['site/activation'],
                            ['class' => 'strong']
                        ) ?>;
                    </li>
                    <li>
                        зайти на сайт под своим логином и паролем;
                    </li>
                    <li>
                        подать заявку на новую или свободную команду;
                    </li>
                    <li>
                        дождаться, пока модератор рассмотрит вашу заявку и отдаст клуб в ваше распоряжение;
                    </li>
                    <li>
                        ознакомиться с самыми простыми разделами правил (по желанию);
                    </li>
                    <li>
                        и всё - приступить к игре! - постепенно вникая в тонкости и детали игрового процесса.
                    </li>
                </ul>
                <p class="text-justify">
                    Свои вопросы вы можете задать опытным игрокам на <?= Html::a('форуме', ['forum/index']) ?>.
                    Обо всех проблемах и вопросах вы можете написать
                    в <?= Html::a('техподдержку сайта', ['support/index']) ?>.
                </p>
            </div>
        </div>
        <?php
        if ($birthdayBoys) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h2>Дни рождения</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-justify">
                        <span class="strong">Сегодня день рождения</span> празднуют менеджеры:
                    </p>
                    <ul>
                        <?php
                        foreach ($birthdayBoys as $item) : ?>
                            <li>
                                <?= $item->fullName ?>
                                (<?= Html::a(Html::encode($item->login), ['user/view', 'id' => $item->id]) ?>)
                                <?php
                                if ($item->birth_year) : ?>
                                    -
                                    <?= date('Y') - $item->birth_year ?>-я годовщина!
                                <?php
                                endif ?>
                            </li>
                        <?php
                        endforeach ?>
                    </ul>
                </div>
            </div>
        <?php
        endif ?>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="row margin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <fieldset class="text-size-3">
                    <legend class="text-center strong">
                        Форум
                    </legend>
                    <?php
                    foreach ($forumMessage as $item): ?>
                        <div class="row margin-top-small">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= Html::a(
                                    $item->forumTheme->name,
                                    ['forum/theme', 'id' => $item->forumTheme->id]
                                ) ?>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= $item->forumTheme->forumGroup->name ?>
                            </div>
                        </div>
                    <?php
                    endforeach ?>
                </fieldset>
            </div>
        </div>
        <div class="row margin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <fieldset>
                    <legend class="text-center strong">
                        Счётчик
                    </legend>
                    <?= Html::img(
                        '//counter.yadro.ru/logo?14.4',
                        [
                            'alt' => 'LiveInternet',
                            'height' => 31,
                            'width' => 88,
                        ]
                    ) ?>
                </fieldset>
            </div>
        </div>
        <div class="row margin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <fieldset>
                    <legend class="text-center strong">
                        Платежи
                    </legend>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <a href="//passport.webmoney.ru/asp/certview.asp?wmid=274662367507" rel="nofollow"
                               target="_blank">
                                <?= Html::img(
                                    '/img/webmoney.png',
                                    [
                                        'alt' => 'WebMoney',
                                        'border' => 0,
                                        'title' => 'WebMoney ID 274662367507',
                                    ]
                                ) ?>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <a href="//www.free-kassa.ru/" rel="nofollow" target="_blank">
                                <?= Html::img(
                                    '//www.free-kassa.ru/img/fk_btn/13.png',
                                    [
                                        'alt' => 'Free Kassa',
                                        'border' => 0,
                                        'title' => 'Free Kassa',
                                    ]
                                ) ?>
                            </a>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
