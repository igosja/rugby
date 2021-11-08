<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\TeamVisitor;
use Exception;
use Yii;

/**
 * Class TruncateTables
 * @package console\models\newSeason
 */
class TruncateTables
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        Yii::$app->db->createCommand()->truncateTable(TeamVisitor::tableName())->execute();
    }
}
