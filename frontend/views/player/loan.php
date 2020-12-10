<?php

// TODO refactor

/**
 * @var AbstractController $context
 * @var LoanApplicationFrom $modelLoanApplicationFrom
 * @var LoanApplicationTo $modelLoanApplicationTo
 * @var LoanFrom $modelLoanFrom
 * @var LoanTo $modelLoanTo
 * @var int $onLoan
 * @var Player $player
 * @var View $this
 */

use common\models\db\Player;
use frontend\controllers\AbstractController;
use frontend\models\forms\LoanApplicationFrom;
use frontend\models\forms\LoanApplicationTo;
use frontend\models\forms\LoanFrom;
use frontend\models\forms\LoanTo;
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
                <th>Аренда игрока</th>
            </tr>
        </table>
    </div>
</div>
<?php if (!$context->myTeam) : ?>
    <?= $this->render('//player/_loan_no_team') ?>
<?php elseif ($player->myPlayer()) : ?>
    <?php if ($onLoan) : ?>
        <?= $this->render('//player/_loan_from', [
            'model' => $modelLoanFrom,
        ]) ?>
    <?php else: ?>
        <?= $this->render('//player/_loan_to', [
            'model' => $modelLoanTo,
        ]) ?>
    <?php endif ?>
<?php else: ?>
    <?php if ($onLoan) : ?>
        <?= $this->render('//player/_loan_application', [
            'model' => $modelLoanApplicationTo,
            'modelFrom' => $modelLoanApplicationFrom,
        ]) ?>
    <?php else: ?>
        <?= $this->render('//player/_loan_no') ?>
    <?php endif ?>
<?php endif ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Аренда игрока</th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//player/_links', ['id' => $player->id]) ?>
    </div>
</div>
