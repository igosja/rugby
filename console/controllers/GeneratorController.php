<?php

// TODO refactor

namespace console\controllers;

use common\components\helpers\ErrorHelper;
use console\models\generator\CheckCaptain;
use console\models\generator\CheckCronDate;
use console\models\generator\CheckLineup;
use console\models\generator\CheckTeamMoodLimit;
use console\models\generator\CountryAuto;
use console\models\generator\CountryStadiumCapacity;
use console\models\generator\CountVisitor;
use console\models\generator\DecreaseInjury;
use console\models\generator\DumpDatabase;
use console\models\generator\FillLineup;
use console\models\generator\FinanceStadium;
use console\models\generator\FriendlyInviteDelete;
use console\models\generator\GameResult;
use console\models\generator\GameRowReset;
use console\models\generator\IncreaseNationalPlayerDay;
use console\models\generator\IncreaseNationalUserDay;
use console\models\generator\InsertAchievement;
use console\models\generator\InsertNews;
use console\models\generator\InsertSwiss;
use console\models\generator\LeagueLot;
use console\models\generator\LeagueOut;
use console\models\generator\LineupToStatistic;
use console\models\generator\LoanCheck;
use console\models\generator\LoanDecreaseAndReturn;
use console\models\generator\MakeLoan;
use console\models\generator\MakePlayed;
use console\models\generator\MakeTransfer;
use console\models\generator\MoodReset;
use console\models\generator\NationalFire;
use console\models\generator\NationalStadium;
use console\models\generator\NationalViceFire;
use console\models\generator\NationalViceVoteStatus;
use console\models\generator\NationalVoteStatus;
use console\models\generator\NationalVs;
use console\models\generator\NewSeason;
use console\models\generator\PlayerGameRow;
use console\models\generator\PlayerLeaguePower;
use console\models\generator\PlayerPowerNewToOld;
use console\models\generator\PlayerPowerS;
use console\models\generator\PlayerPrice;
use console\models\generator\PlayerRealPower;
use console\models\generator\PlayerSpecialToLineup;
use console\models\generator\PlayerTire;
use console\models\generator\PlusMinus;
use console\models\generator\Prize;
use console\models\generator\ReferrerBonus;
use console\models\generator\SetAuto;
use console\models\generator\SetDefaultStyle;
use console\models\generator\SetFreePlayerOnTransfer;
use console\models\generator\SetInjury;
use console\models\generator\SetStadium;
use console\models\generator\SetTicketPrice;
use console\models\generator\SetUserAuto;
use console\models\generator\SiteClose;
use console\models\generator\SiteOpen;
use console\models\generator\Snapshot;
use console\models\generator\StadiumMaintenance;
use console\models\generator\Standing;
use console\models\generator\StandingPlace;
use console\models\generator\TakeSalary;
use console\models\generator\TeamAge;
use console\models\generator\TeamPlayerCount;
use console\models\generator\TeamPowerVs;
use console\models\generator\TeamPrice;
use console\models\generator\TeamToStatistic;
use console\models\generator\TeamVisitorAfterGame;
use console\models\generator\TireBaseLevel;
use console\models\generator\TransferCheck;
use console\models\generator\UpdateBuildingBase;
use console\models\generator\UpdateBuildingStadium;
use console\models\generator\UpdateCronDate;
use console\models\generator\UpdateLeagueCoefficient;
use console\models\generator\UpdatePhysical;
use console\models\generator\UpdateRating;
use console\models\generator\UpdateSchool;
use console\models\generator\UpdateScout;
use console\models\generator\UpdateTeamVisitor;
use console\models\generator\UpdateTraining;
use console\models\generator\UpdateUserRating;
use console\models\generator\UpdateUserTotalRating;
use console\models\generator\UserDecrementAutoForVocation;
use console\models\generator\UserFire;
use console\models\generator\UserFireExtraTeam;
use console\models\generator\UserHolidayEnd;
use console\models\generator\UserToRating;
use Exception;

/**
 * Class GeneratorController
 * @package console\controllers
 */
class GeneratorController extends AbstractController
{
    /**
     * @return void
     */
    public function actionIndex(): void
    {
        $modelArray = [
            new UpdateCronDate,
            new SiteClose,
            new DumpDatabase,
            new PlayerPowerNewToOld,
            new CheckLineup,
            new FillLineup,
            new PlayerSpecialToLineup,
            new CheckCaptain,
            new SetAuto,
            new CheckTeamMoodLimit,
            new SetDefaultStyle,
            new SetUserAuto,
            new SetTicketPrice,
            new SetStadium,
            new CountVisitor,
            new FinanceStadium,
            new TeamToStatistic,
            new UserToRating,
            new LineupToStatistic,
            new NationalVs,
            new GameResult,
            new UpdateLeagueCoefficient,
            new UpdateUserRating,
            new CountryAuto,
            new TeamVisitorAfterGame,
            new UpdateTeamVisitor,
            new PlusMinus,
            new Standing,
            new StandingPlace,
            new PlayerGameRow,
            new PlayerTire,
            new UpdateBuildingBase,
            new UpdateBuildingStadium,
            new UpdateTraining,
            new UpdatePhysical,
            new UpdateSchool,
            new UpdateScout,
            new StadiumMaintenance,
            new DecreaseInjury,
            new SetInjury,
            new MakePlayed,
            new LeagueOut,
            new LeagueLot,
            new InsertAchievement,
            new Prize,
            new InsertSwiss,
            new LoanDecreaseAndReturn,
            new MakeTransfer,
            new TransferCheck,
            new MakeLoan,
            new LoanCheck,
            new TireBaseLevel,
            new GameRowReset,
            new MoodReset,
            new IncreaseNationalUserDay,
            new IncreaseNationalPlayerDay,
            new UserDecrementAutoForVocation,
            new UserFire,
            new UserHolidayEnd,
            new NationalVoteStatus,
            new NationalViceVoteStatus,
            new NationalFire,
            new NationalViceFire,
            new ReferrerBonus,
            new NewSeason,
            new PlayerLeaguePower,
            new PlayerPrice,
            new PlayerPowerS,
            new PlayerRealPower,
            new TakeSalary,
            new TeamPowerVs,
            new TeamPrice,
            new TeamAge,
            new TeamPlayerCount,
            new CountryStadiumCapacity,
            new UpdateUserTotalRating,
            new UpdateRating,
            new InsertNews,
            new FriendlyInviteDelete,
            new UserFireExtraTeam,
            new NationalStadium,
            new SetFreePlayerOnTransfer,
            new Snapshot,
            new SiteOpen,
        ];

        try {
            (new CheckCronDate)->execute();
            $this->progress($modelArray);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }
    }
}
