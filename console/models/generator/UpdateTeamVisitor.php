<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Team;
use common\models\db\TeamVisitor;
use Exception;

/**
 * Class UpdateTeamVisitor
 * @package console\models\generator
 */
class UpdateTeamVisitor
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $visitor = 0;

            /**
             * @var TeamVisitor[] $visitorArray
             */
            $visitorArray = TeamVisitor::find()
                ->where(['team_id' => $team->id])
                ->orderBy(['id' => SORT_DESC])
                ->limit(5)
                ->all();
            foreach ($visitorArray as $item) {
                $visitor += $item->visitor;
            }

            $countVisitor = count($visitorArray);
            if (!$countVisitor) {
                $visitor = 100;
                $countVisitor = 1;
            }

            $visitor = round($visitor / $countVisitor);

            $team->visitor = $visitor;
            $team->save(true, ['visitor']);
        }
    }
}
