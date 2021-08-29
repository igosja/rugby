<?php

// TODO refactor

use common\models\db\LineupTemplate;
use frontend\controllers\AbstractController;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var AbstractController $controller
 * @var LineupTemplate[] $lineupTemplateArray
 */

$controller = Yii::$app->controller;

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <table class="table table-bordered table-hover">
        <tr>
            <th><?= Yii::t('frontend', 'views.lineup.template-table.th.name') ?></th>
            <th class="col-10"></th>
        </tr>
        <?php foreach ($lineupTemplateArray as $lineupTemplate) : ?>
            <tr>
                <td>
                    <?= $lineupTemplate->name ?>
                </td>
                <td class="text-center">
                    <?= Html::a(
                        FAS::icon(FontAwesome::_UPLOAD, ['title' => Yii::t('frontend', 'views.lineup.template-table.link.load')]),
                        'javascript:',
                        [
                            'class' => 'template-load',
                            'data-url' => Url::to(['lineup/template-load', 'id' => $lineupTemplate->lineup_template_id]),
                        ]
                    ) ?>
                    <?php if ($lineupTemplate->team_id === $controller->myTeam->id) : ?>
                        <?= Html::a(
                            FAR::icon(FontAwesome::_TRASH_ALT, ['title' => Yii::t('frontend', 'views.lineup.template-table.link.delete')]),
                            'javascript:',
                            [
                                'class' => 'template-delete',
                                'data-url' => Url::to(['lineup/template-delete', 'id' => $lineupTemplate->lineup_template_id]),
                            ]
                        ) ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
