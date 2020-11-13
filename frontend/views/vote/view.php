<?php

// TODO refactor

/**
 * @var Vote $vote
 */

use common\components\helpers\FormatHelper;
use common\models\db\Vote;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h1>Опрос</h1>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <span class="strong"><?= $vote->text ?></span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $vote->voteStatus->name ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                Автор:
                <?= $vote->user->getUserLink() ?>,
                <?= FormatHelper::asDateTime($vote->date) ?>
            </div>
        </div>
        <?php foreach ($vote->answers() as $answer) : ?>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= $answer['answer'] ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?= $answer['count'] ?>
                    (<?= $answer['percent'] ?>%)
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
