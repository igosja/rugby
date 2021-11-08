<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var User $model
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a(
            'Login',
            ['auth', 'id' => $model->id],
            ['class' => 'btn btn-default', 'target' => '_blank']
        ) ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $attributes = [
            [
                'captionOptions' => ['class' => 'col-lg-6'],
                'label' => 'ID',
                'value' => static function (User $model) {
                    return $model->id;
                },
            ],
            [
                'label' => 'Login',
                'value' => static function (User $model) {
                    return Html::encode($model->login);
                },
            ],
            [
                'label' => 'Email',
                'value' => static function (User $model) {
                    return $model->email;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Money',
                'value' => static function (User $model) {
                    return $model->money . ' ' . Html::a(
                            'Add money',
                            ['pay', 'id' => $model->id],
                            ['class' => 'btn btn-default btn-xs']
                        );
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Last visit',
                'value' => static function (User $model) {
                    return $model->lastVisit();
                },
            ],
            [
                'format' => 'raw',
                'label' => 'IP',
                'value' => static function (User $model) {
                    $result = [];
                    foreach ($model->userLogins as $userLogin) {
                        $result[] = $userLogin->ip;
                    }
                    return implode('<br>', array_unique($result));
                },
            ],
            [
                'label' => 'Register date',
                'value' => static function (User $model) {
                    return FormatHelper::asDateTime($model->date_register);
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Site access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_SITE])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_SITE],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Comment access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_COMMENT])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_COMMENT],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Chat access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_CHAT])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_CHAT],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Deal comment access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_COMMENT_DEAL])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_COMMENT_DEAL],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Forum access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_FORUM])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_FORUM],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Game comment access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_COMMENT_GAME])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_COMMENT_GAME],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
            [
                'format' => 'raw',
                'label' => 'News comment access',
                'value' => static function (User $model) {
                    $userBlock = UserBlock::find()
                        ->andWhere(['user_id' => $model->id])
                        ->andWhere(['user_block_type_id' => UserBlockType::TYPE_COMMENT_NEWS])
                        ->orderBy(['date' => SORT_DESC])
                        ->limit(1)
                        ->one();
                    if ($userBlock && $userBlock->date > time()) {
                        $result = 'Blocked till ' . FormatHelper::asDateTime($userBlock->date);
                    } else {
                        $result = 'Opened ' . Html::a(
                                'Block',
                                ['block', 'id' => $model->id, 'type' => UserBlockType::TYPE_COMMENT_NEWS],
                                ['class' => 'btn btn-default btn-xs']
                            );
                    }
                    return $result;
                },
            ],
        ];
        print DetailView::widget([
            'attributes' => $attributes,
            'model' => $model,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
