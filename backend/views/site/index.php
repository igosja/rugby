<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Payment;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $chat
 * @var int $complaint
 * @var int $countModeration
 * @var int $forumMessage
 * @var int $freeTeam
 * @var int $gameComment
 * @var int $loanComment
 * @var int $logo
 * @var int $news
 * @var int $newsComment
 * @var Payment[] $paymentArray
 * @var array $paymentData
 * @var array $paymentCategories
 * @var int $photo
 * @var int $support
 * @var View $this
 * @var int $transferComment
 * @var int $poll
 */

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title); ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-dribbble fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge admin-freeTeam"><?= $freeTeam; ?></div>
                        <div>Свободные команды</div>
                    </div>
                </div>
            </div>
            <?= Html::a(
                '<div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>',
                ['team/index']
            ); ?>
        </div>
    </div>
    <div
            class="col-lg-3 col-md-6 col-sm-12 col-xs-12 panel-logo"
            <?php if (0 == $logo) : ?>style="display:none;"<?php endif; ?>
    >
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shield fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge admin-logo"><?= $logo; ?></div>
                        <div>Логотипы</div>
                    </div>
                </div>
            </div>
            <?= Html::a(
                '<div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>',
                ['logo/index']
            ); ?>
        </div>
    </div>
    <div
            class="col-lg-3 col-md-6 col-sm-12 col-xs-12 panel-photo"
            <?php if (0 == $photo) : ?>style="display:none;"<?php endif; ?>
    >
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge admin-photo"><?= $photo; ?></div>
                        <div>Фото</div>
                    </div>
                </div>
            </div>
            <?= Html::a(
                '<div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>',
                ['photo/index']
            ); ?>
        </div>
    </div>
    <div
            class="col-lg-3 col-md-6 col-sm-12 col-xs-12 panel-support"
            <?php if (0 == $support) : ?>style="display:none;"<?php endif; ?>
    >
        <div class="panel panel-red panel-support">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge admin-support"><?= $support; ?></div>
                        <div>Тех.поддежка</div>
                    </div>
                </div>
            </div>
            <?= Html::a(
                '<div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>',
                ['support/index']
            ); ?>
        </div>
    </div>
    <div
            class="col-lg-3 col-md-6 col-sm-12 col-xs-12 panel-complaint"
            <?php if (0 == $complaint) : ?>style="display:none;"<?php endif; ?>
    >
        <div class="panel panel-red panel-complaint">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-exclamation-circle fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge admin-complaint"><?= $complaint; ?></div>
                        <div>Жалобы</div>
                    </div>
                </div>
            </div>
            <?= Html::a(
                '<div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>',
                ['complaint/index']
            ); ?>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 panel-poll"
         <?php if (0 == $poll) : ?>style="display:none;"<?php endif; ?>>
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge admin-poll"><?= $poll; ?></div>
                        <div>Опросы</div>
                    </div>
                </div>
            </div>
            <?= Html::a(
                '<div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>',
                ['poll/index']
            ); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Платежи
            </div>
            <div class="panel-body">
                <?php

                try {
                    print Highcharts::widget([
                        'options' => [
                            'credits' => ['enabled' => false],
                            'legend' => ['enabled' => false],
                            'series' => [
                                ['name' => 'Платежи', 'data' => $paymentData],
                            ],
                            'title' => ['text' => 'Платежи'],
                            'xAxis' => [
                                'categories' => $paymentCategories,
                                'title' => ['text' => 'Месяц'],
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'Сумма'],
                            ],
                        ]
                    ]);
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }

                ?>
                <div id="chart-payment"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-condensed">
                        <tr>
                            <th>Время</th>
                            <th>Сумма</th>
                            <th>Пользователь</th>
                        </tr>
                        <?php foreach ($paymentArray as $item) : ?>
                            <tr>
                                <td>
                                    <?= FormatHelper::asDateTime($item->payment_date); ?>
                                </td>
                                <td>
                                    <?= Yii::$app->formatter->asDecimal($item->payment_sum); ?>
                                </td>
                                <td>
                                    <?= Html::a(
                                        Html::encode($item->user->user_login),
                                        ['user/view', 'id' => $item->payment_user_id],
                                        ['target' => '_blank']
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> На проверку (<?= $countModeration; ?>)
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?= Html::a(
                        'Чат <span class="pull-right text-muted small"><em>'
                        . $chat
                        . '</em></span>',
                        ['moderation/chat'],
                        ['class' => 'list-group-item']
                    ); ?>
                    <?= Html::a(
                        'Форум <span class="pull-right text-muted small"><em>'
                        . $forumMessage
                        . '</em></span>',
                        ['moderation/forum-message'],
                        ['class' => 'list-group-item']
                    ); ?>
                    <?= Html::a(
                        'Комментарии к матчам <span class="pull-right text-muted small"><em>'
                        . $gameComment
                        . '</em></span>',
                        ['moderation/game-comment'],
                        ['class' => 'list-group-item']
                    ); ?>
                    <?= Html::a(
                        'Новости <span class="pull-right text-muted small"><em>'
                        . $news
                        . '</em></span>',
                        ['moderation/news'],
                        ['class' => 'list-group-item']
                    ); ?>
                    <?= Html::a(
                        'Комментарии к новостям <span class="pull-right text-muted small"><em>'
                        . $newsComment
                        . '</em></span>',
                        ['moderation/news-comment'],
                        ['class' => 'list-group-item']
                    ); ?>
                    <?= Html::a(
                        'Комментарии к аренде <span class="pull-right text-muted small"><em>'
                        . $loanComment
                        . '</em></span>',
                        ['moderation/loan-comment'],
                        ['class' => 'list-group-item']
                    ); ?>
                    <?= Html::a(
                        'Комментарии к трансферам <span class="pull-right text-muted small"><em>'
                        . $transferComment
                        . '</em></span>',
                        ['moderation/transfer-comment'],
                        ['class' => 'list-group-item']
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</div>