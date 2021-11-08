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
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $divisionArray = Division::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $nationalTypeArray = NationalType::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($nationalTypeArray as $nationalType) {
            $rotateArray = [];

            foreach ($divisionArray as $division) {
                $rotateWorldCup = [];

                $worldCupArray = WorldCup::find()
                    ->where([
                        'division_id' => $division->id - 1,
                        'national_id' => $nationalType->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_ASC])
                    ->offset(8)
                    ->limit(2)
                    ->all();
                if (!$worldCupArray) {
                    $worldCupArray = WorldCup::find()
                        ->where([
                            'division_id' => $division->id,
                            'national_id' => $nationalType->id,
                            'season_id' => $seasonId,
                        ])
                        ->orderBy(['place' => SORT_ASC])
                        ->limit(2)
                        ->all();
                }
                foreach ($worldCupArray as $worldCup) {
                    $rotateWorldCup[] = $worldCup->national_id;
                }

                $worldCupArray = WorldCup::find()
                    ->where([
                        'division_id' => $division->id,
                        'national_type_id' => $nationalType->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_ASC])
                    ->offset(2)
                    ->limit(6)
                    ->all();
                foreach ($worldCupArray as $worldCup) {
                    $rotateWorldCup[] = $worldCup->national_id;
                }

                $worldCupArray = WorldCup::find()
                    ->where([
                        'division_id' => $division->id + 1,
                        'national_type_id' => $nationalType->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_ASC])
                    ->limit(2)
                    ->all();
                if ($worldCupArray) {
                    foreach ($worldCupArray as $worldCup) {
                        $rotateWorldCup[] = $worldCup->national_id;
                    }
                } else {
                    $worldCupArray = WorldCup::find()
                        ->where([
                            'division_id' => $division->id,
                            'national_type_id' => $nationalType->id,
                            'season_id' => $seasonId,
                        ])
                        ->orderBy(['place' => SORT_ASC])
                        ->offset(8)
                        ->limit(2)
                        ->all();
                    foreach ($worldCupArray as $worldCup) {
                        $rotateWorldCup[] = $worldCup->national_id;
                    }
                }

                if ($rotateWorldCup) {
                    $rotateArray[$division->id] = $rotateWorldCup;
                }
            }

            foreach ($divisionArray as $division) {
                if (isset($rotateArray[$division->id])) {
                    $data = [];

                    foreach ($rotateArray[$division->id] as $item) {
                        $data[] = [$nationalType->id, $division->id, $seasonId + 1, $item];
                    }

                    Yii::$app->db
                        ->createCommand()
                        ->batchInsert(
                            WorldCup::tableName(),
                            [
                                'national_type_id',
                                'division_id',
                                'season_id',
                                'national_id'
                            ],
                            $data
                        )
                        ->execute();
                }
            }
        }
    }
}
