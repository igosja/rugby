<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\BuildingBase;
use common\models\ConstructionType;
use common\models\History;
use common\models\HistoryText;
use Exception;

/**
 * Class EndBuildingBase
 * @package console\models\newSeason
 */
class EndBuildingBase
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        BuildingBase::updateAll(['building_base_day' => 0], ['building_base_ready' => 0]);

        $buildingBaseArray = BuildingBase::find()
            ->with(['building', 'team'])
            ->where(['building_base_ready' => 0])
            ->andWhere(['<=', 'building_base_day', 0])
            ->orderBy(['building_base_id' => SORT_ASC])
            ->each();
        foreach ($buildingBaseArray as $buildingBase) {
            /**
             * @var BuildingBase $buildingBase
             */
            $buildingName = $buildingBase->building->building_name;
            $relationName = str_replace('_', '', $buildingName);
            if (strlen($relationName) > 5) {
                $relationName{4} = strtoupper($relationName{4});
            }
            $buildingLevel = $buildingName . '_level';
            $buildingId = 'team_' . $buildingName . '_id';

            if (ConstructionType::BUILD == $buildingBase->building_base_construction_type_id) {
                $buildingBase->team->$buildingId = $buildingBase->team->$buildingId + 1;
                $buildingBase->team->save();

                $historyTextId = HistoryText::BUILDING_UP;
            } else {
                $buildingBase->team->$buildingId = $buildingBase->team->$buildingId - 1;
                $buildingBase->team->save();

                $historyTextId = HistoryText::BUILDING_DOWN;
            }

            History::log([
                'history_building_id' => $buildingBase->building_base_building_id,
                'history_history_text_id' => $historyTextId,
                'history_team_id' => $buildingBase->building_base_team_id,
                'history_value' => $buildingBase->team->$relationName->$buildingLevel,
            ]);
        }

        BuildingBase::updateAll(
            ['building_base_ready' => time()],
            ['and', ['building_base_ready' => 0], ['<=', 'building_base_day', 0]]
        );
    }
}