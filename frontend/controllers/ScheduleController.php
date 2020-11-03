<?php

namespace frontend\controllers;

use common\components\helpers\FormatHelper;
use common\models\db\Season;
use frontend\models\preparers\GamePrepare;
use frontend\models\preparers\SchedulePrepare;
use frontend\models\queries\ScheduleQuery;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ScheduleController
 * @package frontend\controllers
 */
class ScheduleController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);

        $dataProvider = SchedulePrepare::getScheduleDataProvider($seasonId);
        $scheduleId = ScheduleQuery::getCurrentScheduleIds();
        $seasonArray = Season::getSeasonArray();

        $this->setSeoTitle('Расписание');
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'seasonArray' => $seasonArray,
            'seasonId' => $seasonId,
            'scheduleId' => $scheduleId,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $schedule = ScheduleQuery::getScheduleById($id);
        $this->notFound($schedule);

        $dataProvider = GamePrepare::getGameDataProvider($id);

        $this->setSeoTitle(
            'Список матчей игрового дня '
            . FormatHelper::asDate($schedule->schedule_date)
        );
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'schedule' => $schedule,
        ]);
    }
}
