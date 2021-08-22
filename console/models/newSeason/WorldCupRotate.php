<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Division;
use common\models\db\NationalType;
use common\models\db\Season;
use common\models\db\WorldCup;
use Yii;
use yii\db\Exception;

/**
 * Class WorldCupRotate
 * @package console\models\newSeason
 */
class WorldCupRotate
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason();

        $divisionArray = Division::find()
            ->orderBy(['division_id' => SORT_ASC])
            ->all();

        $nationalTypeArray = NationalType::find()
            ->orderBy(['national_type_id' => SORT_ASC])
            ->all();
        foreach ($nationalTypeArray as $nationalType) {
            $rotateArray = [];

            foreach ($divisionArray as $division) {
                $rotateWorldCup = [];

                if (Division::D1 == $division->division_id) {
                    $worldCupArray = WorldCup::find()
                        ->where([
                            'world_cup_division_id' => $division->division_id,
                            'world_cup_national_type_id' => $nationalType->national_type_id,
                            'world_cup_season_id' => $seasonId,
                        ])
                        ->orderBy(['world_cup_place' => SORT_ASC])
                        ->limit(10)
                        ->all();
                    foreach ($worldCupArray as $worldCup) {
                        $rotateWorldCup[] = $worldCup->world_cup_national_id;
                    }

                    $worldCupArray = WorldCup::find()
                        ->where([
                            'world_cup_division_id' => $division->division_id + 1,
                            'world_cup_national_type_id' => $nationalType->national_type_id,
                            'world_cup_season_id' => $seasonId,
                        ])
                        ->orderBy(['world_cup_place' => SORT_ASC])
                        ->limit(2)
                        ->all();
                    if ($worldCupArray) {
                        foreach ($worldCupArray as $worldCup) {
                            $rotateWorldCup[] = $worldCup->world_cup_national_id;
                        }
                    } else {
                        $worldCupArray = WorldCup::find()
                            ->where([
                                'world_cup_division_id' => $division->division_id,
                                'world_cup_national_type_id' => $nationalType->national_type_id,
                                'world_cup_season_id' => $seasonId,
                            ])
                            ->orderBy(['world_cup_place' => SORT_ASC])
                            ->offset(10)
                            ->limit(2)
                            ->all();
                        foreach ($worldCupArray as $worldCup) {
                            $rotateWorldCup[] = $worldCup->world_cup_national_id;
                        }
                    }
                } else {
                    $worldCupArray = WorldCup::find()
                        ->where([
                            'world_cup_division_id' => $division->division_id,
                            'world_cup_national_type_id' => $nationalType->national_type_id,
                            'world_cup_season_id' => $seasonId,
                        ])
                        ->orderBy(['world_cup_place' => SORT_ASC])
                        ->offset(2)
                        ->limit(8)
                        ->all();
                    if ($worldCupArray) {
                        foreach ($worldCupArray as $worldCup) {
                            $rotateWorldCup[] = $worldCup->world_cup_national_id;
                        }

                        $worldCupArray = WorldCup::find()
                            ->where([
                                'world_cup_division_id' => $division->division_id - 1,
                                'world_cup_national_type_id' => $nationalType->national_type_id,
                                'world_cup_season_id' => $seasonId,
                            ])
                            ->orderBy(['world_cup_place' => SORT_ASC])
                            ->offset(10)
                            ->limit(2)
                            ->all();
                        foreach ($worldCupArray as $worldCup) {
                            $rotateWorldCup[] = $worldCup->world_cup_national_id;
                        }

                        $worldCupArray = WorldCup::find()
                            ->where([
                                'world_cup_division_id' => $division->division_id + 1,
                                'world_cup_national_type_id' => $nationalType->national_type_id,
                                'world_cup_season_id' => $seasonId,
                            ])
                            ->orderBy(['world_cup_place' => SORT_ASC])
                            ->limit(2)
                            ->all();
                        if ($worldCupArray) {
                            foreach ($worldCupArray as $worldCup) {
                                $rotateWorldCup[] = $worldCup->world_cup_national_id;
                            }
                        } else {
                            $worldCupArray = WorldCup::find()
                                ->where([
                                    'world_cup_division_id' => $division->division_id,
                                    'world_cup_national_type_id' => $nationalType->national_type_id,
                                    'world_cup_season_id' => $seasonId,
                                ])
                                ->orderBy(['world_cup_place' => SORT_ASC])
                                ->offset(10)
                                ->limit(2)
                                ->all();
                            foreach ($worldCupArray as $worldCup) {
                                $rotateWorldCup[] = $worldCup->world_cup_national_id;
                            }
                        }
                    }
                }

                if ($rotateWorldCup) {
                    $rotateArray[$division->division_id] = $rotateWorldCup;
                }
            }

            foreach ($divisionArray as $division) {
                if (isset($rotateArray[$division->division_id])) {
                    $data = [];

                    foreach ($rotateArray[$division->division_id] as $item) {
                        $data[] = [$nationalType->national_type_id, $division->division_id, $seasonId + 1, $item];
                    }

                    Yii::$app->db
                        ->createCommand()
                        ->batchInsert(
                            WorldCup::tableName(),
                            [
                                'world_cup_national_type_id',
                                'world_cup_division_id',
                                'world_cup_season_id',
                                'world_cup_national_id'
                            ],
                            $data
                        )
                        ->execute();
                }
            }
        }
    }
}
