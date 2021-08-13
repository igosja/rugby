<?php

// TODO refactor

namespace console\models\generator;

use common\components\helpers\ErrorHelper;
use common\models\db\Schedule;
use console\controllers\AbstractController;
use console\models\newSeason\BaseMaintenance;
use console\models\newSeason\ChampionshipRotate;
use console\models\newSeason\CountryAutoReset;
use console\models\newSeason\EndBuildingBase;
use console\models\newSeason\EndBuildingStadium;
use console\models\newSeason\EndSchool;
use console\models\newSeason\EndScout;
use console\models\newSeason\EndTraining;
use console\models\newSeason\FireNational;
use console\models\newSeason\GameRow;
use console\models\newSeason\Injury;
use console\models\newSeason\InsertChampionship;
use console\models\newSeason\InsertConference;
use console\models\newSeason\InsertLeague;
use console\models\newSeason\InsertLeagueCoefficient;
use console\models\newSeason\InsertLeagueParticipant;
use console\models\newSeason\InsertNational;
use console\models\newSeason\InsertNewSeason;
use console\models\newSeason\InsertOffSeason;
use console\models\newSeason\InsertOlympiad;
use console\models\newSeason\InsertSchedule;
use console\models\newSeason\InsertWorldCup;
use console\models\newSeason\LeagueLimit;
use console\models\newSeason\MoodResetAll;
use console\models\newSeason\NationalTransferMoney;
use console\models\newSeason\NoDeal;
use console\models\newSeason\OlderPlayer;
use console\models\newSeason\Pension;
use console\models\newSeason\PensionInform;
use console\models\newSeason\PlayerFromNational;
use console\models\newSeason\PlayerPowerChange;
use console\models\newSeason\PlayerPriceAndSalary;
use console\models\newSeason\PlayerTireBaseLevel;
use console\models\newSeason\RandPhysical;
use console\models\newSeason\TruncateTables;
use console\models\newSeason\VisitorResetAll;
use console\models\newSeason\WorldCupRotate;
use Exception;
use Yii;

/**
 * Class NewSeason
 * @package console\models\generator
 */
class NewSeason
{
    /**
     * @return bool
     */
    public function execute(): bool
    {
        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`-86400, \'%Y-%m-%d\')=CURDATE()')
            ->limit(1)
            ->one();
        if ($schedule) {
            return true;
        }

        $modelArray = [
            new NoDeal(),
            new FireNational(),
            new PlayerFromNational(),
            new NationalTransferMoney(),
            new InsertLeagueParticipant(),
            new InsertLeagueCoefficient(),
            new LeagueLimit(),
            new InsertSchedule(),
            new InsertNational(),
            new ChampionshipRotate(),
            new WorldCupRotate(),
            new InsertOffSeason(),
            new InsertConference(),
            new InsertChampionship(),
            new InsertLeague(),
            new InsertWorldCup(),
            new InsertOlympiad(),
            new EndBuildingBase(),
            new EndBuildingStadium(),
            new RandPhysical(),
            new PlayerTireBaseLevel(),
            new EndTraining(),
            new EndSchool(),
            new EndScout(),
            new PlayerPowerChange(),
            new Injury(),
            new Pension(),
            new OlderPlayer(),
            new PensionInform(),
            new PlayerPriceAndSalary(),
            new BaseMaintenance(),
            new MoodResetAll(),
            new VisitorResetAll(),
            new GameRow(),
            new CountryAutoReset(),
            new TruncateTables(),
            new InsertNewSeason(),
        ];

        try {
            /**
             * @var AbstractController $controller
             */
            $controller = Yii::$app->controller;
            $controller->progress($modelArray);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        return true;
    }
}
