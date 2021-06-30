<?php

// TODO refactor

/**
 * @var Federation $federation
 * @var LeagueDistribution $leagueDistribution
 * @var ParticipantLeague[] $participantLeague
 * @var array $teamArray
 */

use common\models\db\Federation;
use common\models\db\LeagueDistribution;
use common\models\db\ParticipantLeague;

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<?php if ($leagueDistribution) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th class="col-20"><?= Yii::t('frontend', 'views.federation.league.th.season') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.federation.league.th.group') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.federation.league.th.q3') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.federation.league.th.q2') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.federation.league.th.q1') ?></th>
                </tr>
                <tr>
                    <td class="text-center"><?= $leagueDistribution->season_id ?></td>
                    <td class="text-center"><?= $leagueDistribution->group ?></td>
                    <td class="text-center"><?= $leagueDistribution->qualification_3 ?></td>
                    <td class="text-center"><?= $leagueDistribution->qualification_2 ?></td>
                    <td class="text-center"><?= $leagueDistribution->qualification_1 ?></td>
                </tr>
                <tr>
                    <th><?= Yii::t('frontend', 'views.federation.league.th.season') ?></th>
                    <th><?= Yii::t('frontend', 'views.federation.league.th.group') ?></th>
                    <th><?= Yii::t('frontend', 'views.federation.league.th.q3') ?></th>
                    <th><?= Yii::t('frontend', 'views.federation.league.th.q2') ?></th>
                    <th><?= Yii::t('frontend', 'views.federation.league.th.q1') ?></th>
                </tr>
            </table>
        </div>
    </div>
<?php endif ?>
<?php foreach ($teamArray as $season => $participantLeague) : ?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
            <?= $season ?> <?= Yii::t('frontend', 'views.federation.league.season') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.team') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.federation.league.th.stage') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.win') ?>"><?= Yii::t('frontend', 'views.th.win') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.draw') ?>"><?= Yii::t('frontend', 'views.th.draw') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.loose') ?>"><?= Yii::t('frontend', 'views.th.loose') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.point') ?>"><?= Yii::t('frontend', 'views.th.point') ?></th>
                </tr>
                <?php foreach ($participantLeague as $item) : ?>
                    <tr>
                        <td>
                            <?= $item->team->getTeamLink() ?>
                        </td>
                        <td class="text-center"><?= $item->stageIn->name ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->win ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->draw ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->loose ?></td>
                        <td class="text-center strong"><?= $item->leagueCoefficient->point ?></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.team') ?></th>
                    <th><?= Yii::t('frontend', 'views.federation.league.th.stage') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.win') ?>"><?= Yii::t('frontend', 'views.th.win') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.draw') ?>"><?= Yii::t('frontend', 'views.th.draw') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.loose') ?>"><?= Yii::t('frontend', 'views.th.loose') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.point') ?>"><?= Yii::t('frontend', 'views.th.point') ?></th>
                </tr>
            </table>
        </div>
    </div>
<?php endforeach ?>
