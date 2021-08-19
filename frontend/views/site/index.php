<?php

// TODO refactor

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
                <h1><?= Yii::t('frontend', 'views.site.index.h1') ?></h1>
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.1') ?>
                </p>
                <h4><?= Yii::t('frontend', 'views.site.index.h4.1') ?></h4>
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.2') ?>
                </p>
                <?php if (Yii::$app->user->isGuest) : ?>
                    <p class="text-center">
                        <?= Html::a(
                            Yii::t('frontend', 'views.site.index.link.signup'),
                            ['sign-up'],
                            ['class' => 'btn']
                        ) ?>
                    </p>
                <?php endif ?>
                <h4><?= Yii::t('frontend', 'views.site.index.h4.2') ?></h4>
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.3') ?>
                </p>
                <h4 class="center header"><?= Yii::t('frontend', 'views.site.index.h4.3') ?></h4>
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.4') ?>
                </p>
                <h4 class="center header"><?= Yii::t('frontend', 'views.site.index.h4.4') ?></h4>
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.5') ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2><?= Yii::t('frontend', 'views.site.index.h2.1') ?></h2>
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
                    <?= $news->user->getUserLink() ?>
                    <p class="text-justify text-size-3">
                        [<?= Html::a(Yii::t('frontend', 'link.details'), ['news/index']) ?>]
                    </p>
                </div>
            </div>
        <?php endif ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2><?= Yii::t('frontend', 'views.site.index.h2.2') ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.6') ?></p>
                <ul>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.1', [
                            'link' => Html::a(
                                Yii::t('frontend', 'views.site.index.link.signup-game'),
                                ['sign-up'],
                                ['class' => 'strong']
                            )
                        ]) ?>;
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.2', [
                            'link' => Html::a(Yii::t('frontend', 'views.site.index.link.activation'),
                                ['activation'],
                                ['class' => 'strong']
                            )
                        ]) ?>;
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.3') ?>;
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.4') ?>;
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.5') ?>;
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.6') ?>;
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.site.index.li.7') ?>.
                    </li>
                </ul>
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.site.index.p.7', [
                        'forum' => Html::a(Yii::t('frontend', 'views.site.index.link.forum'), ['forum/index']),
                        'support' => Html::a(Yii::t('frontend', 'views.site.index.link.support'), ['support/index']),
                    ]) ?>
                </p>
            </div>
        </div>
        <?php if ($birthdayBoys) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h2><?= Yii::t('frontend', 'views.site.index.h2.3') ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-justify">
                        <?= Yii::t('frontend', 'views.site.index.p.8') ?>
                    </p>
                    <ul>
                        <?php foreach ($birthdayBoys as $item) : ?>
                            <li>
                                <?= $item->fullName ?>
                                (<?= Html::a(Html::encode($item->login), ['user/view', 'id' => $item->id]) ?>)
                                <?php if ($item->birth_year) : ?>
                                    - <?= Yii::t('frontend', 'views.site.index.li.birth', [
                                        'age' => date('Y') - $item->birth_year
                                    ]) ?>;
                                <?php endif ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="row margin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <fieldset class="text-size-3">
                    <legend class="text-center strong">
                        <?= Yii::t('frontend', 'views.site.index.legend.1') ?>
                    </legend>
                    <?php foreach ($forumMessage as $item): ?>
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
                    <?php endforeach ?>
                </fieldset>
            </div>
        </div>
        <div class="row margin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <fieldset>
                    <legend class="text-center strong">
                        <?= Yii::t('frontend', 'views.site.index.legend.2') ?>
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
                        <?= Yii::t('frontend', 'views.site.index.legend.3') ?>
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
                </fieldset>
            </div>
        </div>
    </div>
</div>
