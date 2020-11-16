<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use yii\helpers\Html;

/**
 * @var Team $team
 */

?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-size-2">
    <span class="italic">Последние темы на форуме федерации:</span>
    <?php foreach ($team->forumLastArray() as $item) : ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Html::a(
                    $item->forumTheme->name,
                    ['forum/theme/view', 'id' => $item->forumTheme->id]
                ) ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= FormatHelper::asDateTime($item->date) ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
