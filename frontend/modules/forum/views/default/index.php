<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\ForumChapter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ForumChapter[] $forumChapterArray
 * @var array $myFederationArray
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?= Yii::t('frontend', 'modules.views.default.index.h1') ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?= $this->render('/default/_searchForm') ?>
            </div>
        </div>
        <?php foreach ($forumChapterArray as $forumChapter) : ?>
            <div class="row margin-top forum-row-head">
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::a(
                        $forumChapter->name,
                        ['chapter/view', 'id' => $forumChapter->id]
                    ) ?>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                    <span class="hidden-lg hidden-md hidden-sm"
                          title="<?= Yii::t('frontend', 'modules.views.default.index.theme') ?>">
                        <?= Yii::t('frontend', 'modules.views.default.index.theme-short') ?>
                    </span>
                    <span class="hidden-xs"><?= Yii::t('frontend', 'modules.views.default.index.theme') ?></span>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                    <span class="hidden-lg hidden-md"
                          title="<?= Yii::t('frontend', 'modules.views.default.index.message') ?>"><?= Yii::t('frontend', 'modules.views.default.index.message-short') ?></span>
                    <span class="hidden-sm hidden-xs"><?= Yii::t('frontend', 'modules.views.default.index.message') ?></span>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <?= Yii::t('frontend', 'modules.views.default.index.last') ?>
                </div>
            </div>
            <?php if (ForumChapter::NATIONAL !== $forumChapter->id): ?>
                <?php foreach ($forumChapter->forumGroups as $forumGroup) : ?>
                    <div class="row forum-row">
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
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
                                                'theme/view',
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
            <?php else: ?>
                <?php foreach ($forumChapter->forumGroups as $forumGroup) : ?>
                    <?php if (ArrayHelper::isIn($forumGroup->federation_id, $myFederationArray, true)) : ?>
                        <div class="row forum-row">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
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
                                                    'theme/view',
                                                    'id' => $forumGroup->lastForumMessage->id
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
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
