<?php

// TODO refactor

use common\models\db\LineupTemplate;
use frontend\controllers\AbstractController;
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
            <th>Название</th>
            <th class="col-10"></th>
        </tr>
        <?php foreach ($lineupTemplateArray as $lineupTemplate) : ?>
            <tr>
                <td>
                    <?= $lineupTemplate->name ?>
                </td>
                <td class="text-center">
                    <?= Html::a(
                        '<i class="fa fa-upload" aria-hidden="true" title="Загрузить"></i>',
                        'javascript:',
                        [
                            'class' => 'template-load',
                            'data-url' => Url::to(['lineup/template-load', 'id' => $lineupTemplate->lineup_template_id]),
                        ]
                    ); ?>
                    <?php if ($lineupTemplate->lineup_template_team_id == $controller->myTeam->team_id) : ?>
                        <?= Html::a(
                            '<i class="fa fa-trash-o" aria-hidden="true" title="Удалить"></i>',
                            'javascript:',
                            [
                                'class' => 'template-delete',
                                'data-url' => Url::to(['lineup/template-delete', 'id' => $lineupTemplate->lineup_template_id]),
                            ]
                        ); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>