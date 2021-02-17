<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\ForumChapter;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ForumChapter $forumChapter
 * @var View $this
 */

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
                <?= Html::a(
                    Yii::t('frontend', 'modules.forum.views.bread.forum'),
                    ['default/index']
                ) ?>
                /
                <?= $forumChapter->name ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?= $forumChapter->name ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?= $this->render('/default/_searchForm') ?>
            </div>
        </div>
        <div class="row forum-row-head">
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                <?= Yii::t('frontend', 'modules.forum.views.chapter.chapter') ?>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                <span class="hidden-lg hidden-md hidden-sm"
                      title="<?= Yii::t('frontend', 'modules.forum.views.chapter.title.theme') ?>"><?= Yii::t('frontend', 'modules.forum.views.chapter.th.theme') ?></span>
                <span class="hidden-xs"><?= Yii::t('frontend', 'modules.forum.views.chapter.title.theme') ?></span>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                <span class="hidden-lg hidden-md"
                      title="<?= Yii::t('frontend', 'modules.forum.views.chapter.title.message') ?>"><?= Yii::t('frontend', 'modules.forum.views.chapter.th.message') ?></span>
                <span class="hidden-sm hidden-xs"><?= Yii::t('frontend', 'modules.forum.views.chapter.title.message') ?></span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <?= Yii::t('frontend', 'modules.forum.views.chapter.last') ?>
            </div>
        </div>
        <?php foreach ($forumChapter->forumGroups as $forumGroup) : ?>
            <div class="row forum-row">
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?= Html::a(
                                $forumGroup->name,
                                ['group/view', 'id' => $forumGroup->id]
                            ) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
                            <?= $forumGroup->description ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                    <?= $forumGroup->countTheme() ?>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                    <?= $forumGroup->countMessage() ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-size-2">
                    <?php if ($forumGroup->lastForumMessage) : ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= Html::a(
                                    $forumGroup->lastForumMessage->forumTheme->name,
                                    [
                                        'forum/theme',
                                        'id' => $forumGroup->lastForumMessage->forum_theme_id
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= FormatHelper::asDatetime($forumGroup->lastForumMessage->date) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= $forumGroup->lastForumMessage->user->getUserLink(['color' => true]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
