<?php

// TODO refactor

/**
 * @var ElectionNationalApplication $electionNationalApplication
 * @var Federation $federation
 */

use common\models\db\ElectionNationalApplication;
use common\models\db\Federation;

print $this->render('/../modules/federation/views/default/_federation', ['federation' => $federation]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h4><?= Yii::t('frontend', 'views.national-election.player.h4') ?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <p><?= Yii::t('frontend', 'views.national-election.player.candidate') ?> <?= $electionNationalApplication->user->getUserLink() ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                        <th class="col-5"
                            title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                        <th class="col-5"
                            title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                        <th class="col-5"
                            title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.th.power') ?></th>
                        <th class="col-10 hidden-xs"
                            title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                        <th class="col-40"><?= Yii::t('frontend', 'views.th.team') ?></th>
                    </tr>
                    <?php foreach ($electionNationalApplication->electionNationalPlayers as $electionNationalPlayer) : ?>
                        <tr>
                            <td><?= $electionNationalPlayer->player->getPlayerLink() ?></td>
                            <td class="text-center"><?= $electionNationalPlayer->player->position() ?></td>
                            <td class="text-center"><?= $electionNationalPlayer->player->age ?></td>
                            <td class="text-center"><?= $electionNationalPlayer->player->power_nominal ?></td>
                            <td class="hidden-xs text-center"><?= $electionNationalPlayer->player->special() ?></td>
                            <td><?= $electionNationalPlayer->player->team->getTeamImageLink() ?></td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                        <th title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                        <th title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                        <th title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.title.power') ?></th>
                        <th class="hidden-xs"
                            title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                        <th><?= Yii::t('frontend', 'views.th.team') ?></th>
                    </tr>
                </table>
            </div>
        </div>
        <?= $this->render('//site/_show-full-table') ?>
    </div>
</div>
