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
                <h1>Форум</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?= $this->render('/default/_searchForm') ?>
            </div>
        </div>
        <?php

// TODO refactor foreach ($forumChapterArray as $forumChapter) : ?>
            <div class="row margin-top forum-row-head">
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::a(
                        $forumChapter->name,
                        ['chapter/view', 'id' => $forumChapter->id]
                    ) ?>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                    <span class="hidden-lg hidden-md hidden-sm" title="Темы">Т</span>
                    <span class="hidden-xs">Темы</span>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                    <span class="hidden-lg hidden-md" title="Сообщения">C</span>
                    <span class="hidden-sm hidden-xs">Сообщения</span>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    Последнее сообщение
                </div>
            </div>
            <?php

// TODO refactor if (ForumChapter::NATIONAL !== $forumChapter->id): ?>
                <?php

// TODO refactor foreach ($forumChapter->forumGroups as $forumGroup) : ?>
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
                            <?php

// TODO refactor if ($forumGroup->lastForumMessage) : ?>
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
                            <?php

// TODO refactor endif; ?>
                        </div>
                    </div>
                <?php

// TODO refactor endforeach; ?>
            <?php

// TODO refactor else: ?>
                <?php

// TODO refactor foreach ($forumChapter->forumGroups as $forumGroup) : ?>
                    <?php

// TODO refactor if (ArrayHelper::isIn($forumGroup->federation_id, $myFederationArray, true)) : ?>
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
                                <?php

// TODO refactor if ($forumGroup->lastForumMessage) : ?>
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
                                <?php

// TODO refactor endif; ?>
                            </div>
                        </div>
                    <?php

// TODO refactor endif; ?>
                <?php

// TODO refactor endforeach; ?>
            <?php

// TODO refactor endif; ?>
        <?php

// TODO refactor endforeach; ?>
    </div>
</div>
