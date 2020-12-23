<?php

// TODO refactor

/**
 * @var Federation $federation
 * @var LeagueDistribution $leagueDistribution
 * @var array $teamArray
 */

use common\models\db\Federation;
use common\models\db\LeagueDistribution;

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<?php if ($leagueDistribution) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th class="col-20">Сезон</th>
                    <th class="col-20">Групповой этап</th>
                    <th class="col-20">ОР3</th>
                    <th class="col-20">ОР2</th>
                    <th class="col-20">ОР1</th>
                </tr>
                <tr>
                    <td class="text-center"><?= $leagueDistribution->season_id ?></td>
                    <td class="text-center"><?= $leagueDistribution->group ?></td>
                    <td class="text-center"><?= $leagueDistribution->qualification_3 ?></td>
                    <td class="text-center"><?= $leagueDistribution->qualification_2 ?></td>
                    <td class="text-center"><?= $leagueDistribution->qualification_1 ?></td>
                </tr>
                <tr>
                    <th>Сезон</th>
                    <th>Групповой этап</th>
                    <th>ОР3</th>
                    <th>ОР2</th>
                    <th>ОР1</th>
                </tr>
            </table>
        </div>
    </div>
<?php endif ?>
<?php foreach ($teamArray as $season => $participantLeague) : ?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
            <?= $season ?> сезон
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Команда</th>
                    <th class="col-20">Стадия</th>
                    <th class="col-5" title="Победы">В</th>
                    <th class="col-5" title="Победы в овертайте/по буллитам">ВО</th>
                    <th class="col-5" title="Ничьи и поражения в овертайте/по буллитам">Н/ПО</th>
                    <th class="col-5" title="Поражения">П</th>
                    <th class="col-5" title="Очки">О</th>
                </tr>
                <?php foreach ($participantLeague as $item) : ?>
                    <tr>
                        <td>
                            <?= $item->team->teamLink('string', true) ?>
                        </td>
                        <td class="text-center"><?= $item->stage->stage_name ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->lwin ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->lwin_overtime ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->lloose_overtime ?></td>
                        <td class="text-center"><?= $item->leagueCoefficient->lloose ?></td>
                        <td class="text-center strong"><?= $item->leagueCoefficient->lpoint ?></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th>Команда</th>
                    <th>Стадия</th>
                    <th title="Победы">В</th>
                    <th title="Победы в овертайте/по буллитам">ВО</th>
                    <th title="Поражения в овертайте/по буллитам">ПО</th>
                    <th title="Поражения">П</th>
                    <th title="Очки">О</th>
                </tr>
            </table>
        </div>
    </div>
<?php endforeach ?>
