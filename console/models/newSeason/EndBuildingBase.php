<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\BuildingBase;
use common\models\db\ConstructionType;
use common\models\db\History;
use common\models\db\HistoryText;
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
    public function execute(): void
    {
        BuildingBase::updateAll(['day' => 0], ['ready' => null]);

        $buildingBaseArray = BuildingBase::find()
            ->with(['building', 'team'])
            ->where(['ready' => null])
            ->andWhere(['<=', 'day', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($buildingBaseArray as $buildingBase) {
            /**
             * @var BuildingBase $buildingBase
             */
            $buildingName = $buildingBase->building->name;
            $relationName = str_replace('_', '', $buildingName);
            if (strlen($relationName) > 5) {
                $relationName[4] = strtoupper($relationName[4]);
            }
            $buildingLevel = 'level';
            $buildingId = $buildingName . '_id';

            if (ConstructionType::BUILD === $buildingBase->construction_type_id) {
                $buildingBase->team->$buildingId++;
                $buildingBase->team->save(true, [$buildingId]);

                $historyTextId = HistoryText::BUILDING_UP;
            } else {
                $buildingBase->team->$buildingId--;
                $buildingBase->team->save(true, [$buildingId]);

                $historyTextId = HistoryText::BUILDING_DOWN;
            }

            History::log([
                'building_id' => $buildingBase->building_id,
                'history_text_id' => $historyTextId,
                'team_id' => $buildingBase->team_id,
                'value' => $buildingBase->team->$relationName->$buildingLevel,
            ]);
        }

        BuildingBase::updateAll(
            ['ready' => time()],
            ['and', ['ready' => null], ['<=', 'day', 0]]
        );
    }
}