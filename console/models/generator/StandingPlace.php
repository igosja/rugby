<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\League;
use common\models\db\OffSeason;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use common\models\db\WorldCup;
use Exception;

/**
 * Class StandingPlace
 * @package console\models\generator
 *
 * @property int $seasonId
 */
class StandingPlace
{
    private $seasonId;

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->seasonId = Season::getCurrentSeason();

        /**
         * @var Schedule[] $scheduleArray
         */
        $scheduleArray = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($scheduleArray as $schedule) {
            if (TournamentType::CONFERENCE === $schedule->tournament_type_id) {
                $this->conference();
            } elseif (TournamentType::OFF_SEASON === $schedule->tournament_type_id) {
                $this->offSeason();
            } elseif (TournamentType::CHAMPIONSHIP === $schedule->tournament_type_id) {
                $this->championship();
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id &&
                $schedule->stage_id >= Stage::TOUR_LEAGUE_1 &&
                $schedule->stage_id <= Stage::TOUR_LEAGUE_6) {
                $this->league();
            } elseif (TournamentType::NATIONAL === $schedule->tournament_type_id) {
                $this->worldCup();
            }
        }
    }

    /**
     * @throws Exception
     */
    private function conference(): void
    {
        /**
         * @var Conference[] $conferenceArray
         */
        $conferenceArray = Conference::find()
            ->joinWith(['team'])
            ->where(['season_id' => $this->seasonId])
            ->orderBy([
                'point' => SORT_DESC,
                'win' => SORT_DESC,
                'draw' => SORT_DESC,
                'difference' => SORT_DESC,
                'point_for' => SORT_DESC,
                'power_vs' => SORT_ASC,
                'team_id' => SORT_ASC,
            ])
            ->all();
        foreach ($conferenceArray as $i => $model) {
            $model->place = $i + 1;
            $model->save(true, ['place']);
        }
    }

    /**
     * @throws Exception
     */
    private function offSeason(): void
    {
        /**
         * @var OffSeason[] $offSeasonArray
         */
        $offSeasonArray = OffSeason::find()
            ->joinWith(['team'], false)
            ->where(['season_id' => $this->seasonId])
            ->orderBy([
                'point' => SORT_DESC,
                'win' => SORT_DESC,
                'draw' => SORT_DESC,
                'difference' => SORT_DESC,
                'point_for' => SORT_DESC,
                'power_vs' => SORT_ASC,
                'team_id' => SORT_ASC,
            ])
            ->all();
        foreach ($offSeasonArray as $i => $model) {
            $model->place = $i + 1;
            $model->save(true, ['place']);
        }
    }

    /**
     * @throws Exception
     */
    private function championship(): void
    {
        /**
         * @var Championship[] $federationArray
         */
        $federationArray = Championship::find()
            ->where(['season_id' => $this->seasonId])
            ->groupBy(['federation_id'])
            ->orderBy(['federation_id' => SORT_ASC])
            ->all();
        foreach ($federationArray as $federation) {
            /**
             * @var Championship[] $divisionArray
             */
            $divisionArray = Championship::find()
                ->where([
                    'federation_id' => $federation->federation_id,
                    'season_id' => $this->seasonId,
                ])
                ->groupBy(['division_id'])
                ->orderBy(['division_id' => SORT_ASC])
                ->all();
            foreach ($divisionArray as $division) {
                /**
                 * @var Championship[] $championshipArray
                 */
                $championshipArray = Championship::find()
                    ->joinWith(['team'], false)
                    ->where([
                        'federation_id' => $federation->federation_id,
                        'division_id' => $division->division_id,
                        'season_id' => $this->seasonId,
                    ])
                    ->orderBy([
                        'point' => SORT_DESC,
                        'win' => SORT_DESC,
                        'draw' => SORT_DESC,
                        'difference' => SORT_DESC,
                        'point_for' => SORT_DESC,
                        'power_vs' => SORT_ASC,
                        'team_id' => SORT_ASC,
                    ])
                    ->all();
                foreach ($championshipArray as $i => $model) {
                    $model->place = $i + 1;
                    $model->save(true, ['place']);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    public function league(): void
    {
        /**
         * @var League[] $groupArray
         */
        $groupArray = League::find()
            ->where(['season_id' => $this->seasonId])
            ->groupBy(['group'])
            ->orderBy(['group' => SORT_ASC])
            ->all();
        foreach ($groupArray as $group) {
            /**
             * @var League[] $leagueArray
             */
            $leagueArray = League::find()
                ->joinWith(['team'], false)
                ->where([
                    'group' => $group->group,
                    'season_id' => $this->seasonId,
                ])
                ->orderBy([
                    'point' => SORT_DESC,
                    'win' => SORT_DESC,
                    'draw' => SORT_DESC,
                    'difference' => SORT_DESC,
                    'point_for' => SORT_DESC,
                    'power_vs' => SORT_ASC,
                    'team_id' => SORT_ASC,
                ])
                ->all();
            foreach ($leagueArray as $i => $model) {
                $model->place = $i + 1;
                $model->save(true, ['place']);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function worldCup(): void
    {
        /**
         * @var WorldCup[] $nationalTypeArray
         */
        $nationalTypeArray = WorldCup::find()
            ->where(['season_id' => $this->seasonId])
            ->groupBy(['national_type_id'])
            ->orderBy(['national_type_id' => SORT_ASC])
            ->all();
        foreach ($nationalTypeArray as $nationalType) {
            /**
             * @var WorldCup[] $divisionArray
             */
            $divisionArray = WorldCup::find()
                ->where([
                    'season_id' => $this->seasonId,
                    'national_type_id' => $nationalType->national_type_id,
                ])
                ->groupBy(['division_id'])
                ->orderBy(['division_id' => SORT_ASC])
                ->all();
            foreach ($divisionArray as $division) {
                /**
                 * @var WorldCup[] $worldCupArray
                 */
                $worldCupArray = WorldCup::find()
                    ->joinWith(['national'], false)
                    ->where([
                        'division_id' => $division->division_id,
                        'national_type_id' => $nationalType->national_type_id,
                        'season_id' => $this->seasonId,
                    ])
                    ->orderBy([
                        'point' => SORT_DESC,
                        'win' => SORT_DESC,
                        'draw' => SORT_DESC,
                        'difference' => SORT_DESC,
                        'point_for' => SORT_DESC,
                        'power_vs' => SORT_ASC,
                        'national_id' => SORT_ASC,
                    ])
                    ->all();
                foreach ($worldCupArray as $i => $model) {
                    $model->place = $i + 1;
                    $model->save(true, ['place']);
                }
            }
        }
    }
}
