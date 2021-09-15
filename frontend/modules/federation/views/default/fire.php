<?php

// TODO refactor

use common\models\db\Federation;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var Federation $federation
 * @var int $id
 * @var View $this
 */

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-bordered">
            <tr>
                <th><?= Yii::t('frontend', 'views.federation.fire.title') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p><?= Yii::t('frontend', 'views.federation.fire.p') ?></p>
        <?= Html::a(Yii::t('frontend', 'views.federation.fire.link'), ['federation/default/fire', 'id' => $id, 'ok' => 1], ['class' => 'btn margin']) ?>
    </div>
</div>
