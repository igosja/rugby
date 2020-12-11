<?php

// TODO refactor

/**
 * @var AbstractController $context
 * @var TransferApplicationFrom $modelTransferApplicationFrom
 * @var TransferApplicationTo $modelTransferApplicationTo
 * @var TransferFrom $modelTransferFrom
 * @var TransferTo $modelTransferTo
 * @var int $onTransfer
 * @var Player $player
 * @var View $this
 */

use common\models\db\Player;
use frontend\controllers\AbstractController;
use frontend\models\forms\TransferApplicationFrom;
use frontend\models\forms\TransferApplicationTo;
use frontend\models\forms\TransferFrom;
use frontend\models\forms\TransferTo;
use yii\web\View;

$context = $this->context;

print $this->render('//player/_player', ['player' => $player]);

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//player/_links', ['id' => $player->id]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Трансфер игрока</th>
            </tr>
        </table>
    </div>
</div>
<?php if (!$context->myTeam) : ?>
    <?= $this->render('//player/_transfer_no_team') ?>
<?php elseif ($player->myPlayer()) : ?>
    <?php if ($onTransfer) : ?>
        <?= $this->render('//player/_transfer_from', [
            'model' => $modelTransferFrom,
        ]) ?>
    <?php else: ?>
        <?= $this->render('//player/_transfer_to', [
            'model' => $modelTransferTo,
        ]) ?>
    <?php endif ?>
<?php else: ?>
    <?php if ($onTransfer) : ?>
        <?= $this->render('//player/_transfer_application', [
            'model' => $modelTransferApplicationTo,
            'modelFrom' => $modelTransferApplicationFrom,
        ]) ?>
    <?php else: ?>
        <?= $this->render('//player/_transfer_no') ?>
    <?php endif ?>
<?php endif ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Трансфер игрока</th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//player/_links', ['id' => $player->id]) ?>
    </div>
</div>
