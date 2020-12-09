<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\NationalPlayerDay;
use common\models\NationalUserDay;
use common\models\PhysicalChange;
use common\models\TeamVisitor;
use common\models\Teamwork;
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
    public function execute()
    {
        Yii::$app->db->createCommand()->truncateTable(TeamVisitor::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(PhysicalChange::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(NationalPlayerDay::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(NationalUserDay::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(Teamwork::tableName())->execute();
    }
}
