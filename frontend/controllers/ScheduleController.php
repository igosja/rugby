<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\FormatHelper;
use common\models\db\Schedule;
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
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

        $dataProvider = SchedulePrepare::getScheduleDataProvider($seasonId);
        $scheduleId = ScheduleQuery::getCurrentScheduleIds();
        $seasonArray = Season::getSeasonArray();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.schedule.index.title'));
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
        /**
         * @var Schedule $schedule
         */
        $schedule = ScheduleQuery::getScheduleById($id);
        $this->notFound($schedule);

        $dataProvider = GamePrepare::getGameDataProvider($id);

        $this->setSeoTitle(
            Yii::t('frontend', 'controllers.schedule.view.title')
            . FormatHelper::asDate($schedule->date)
        );
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'schedule' => $schedule,
        ]);
    }
}
