<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\BuildingStadium;
use common\models\db\ConstructionType;
use common\models\db\History;
use common\models\db\HistoryText;
use Exception;

/**
 * Class UpdateBuildingStadium
 * @package console\models\generator
 */
class UpdateBuildingStadium
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        BuildingStadium::updateAllCounters(['day' => -1], ['ready' => null]);

        $buildingStadiumArray = BuildingStadium::find()
            ->where(['ready' => null])
            ->andWhere(['<=', 'day', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($buildingStadiumArray as $buildingStadium) {
            /**
             * @var BuildingStadium $buildingStadium
             */
            if (ConstructionType::BUILD === $buildingStadium->construction_type_id) {
                $historyTextId = HistoryText::STADIUM_UP;
            } else {
                $historyTextId = HistoryText::STADIUM_DOWN;
            }

            $buildingStadium->team->stadium->capacity = $buildingStadium->capacity;
            $buildingStadium->team->stadium->save(true, ['capacity']);

            History::log([
                'history_text_id' => $historyTextId,
                'team_id' => $buildingStadium->team_id,
                'value' => $buildingStadium->capacity,
            ]);
        }

        BuildingStadium::updateAll(
            ['ready' => time()],
            ['and', ['ready' => null], ['<=', 'day', 0]]
        );
    }
}
