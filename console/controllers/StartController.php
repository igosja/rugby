<?php

// TODO refactor

namespace console\controllers;

use common\components\helpers\ErrorHelper;
use console\components\AbstractController;
use console\models\start\InsertChampionship;
use console\models\start\InsertConference;
use console\models\start\InsertName;
use console\models\start\InsertNational;
use console\models\start\InsertOffSeason;
use console\models\start\InsertSchedule;
use console\models\start\InsertSurname;
use console\models\start\InsertTeam;
use Exception;

/**
 * Class StartController
 * @package console\controllers
 */
class StartController extends AbstractController
{
    /**
     * @return void
     */
    public function actionIndex()
    {
        $modelArray = [
            new InsertName(),
            new InsertSurname(),
            new InsertTeam(),
            new InsertNational(),
            new InsertSchedule(),
            new InsertOffSeason(),
            new InsertChampionship(),
            new InsertConference(),
        ];

        try {
            $this->progress($modelArray);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }
    }
}
