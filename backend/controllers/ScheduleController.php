<?php

// TODO refactor

namespace backend\controllers;

use common\models\db\Schedule;
use Yii;
use yii\web\Response;

/**
 * Class ScheduleController
 * @package backend\controllers
 */
class ScheduleController extends AbstractController
{
    /**
     * @param int|null $id
     * @return string|Response
     */
    public function actionIndex($id = null)
    {
        if ($id) {
            Schedule::updateAllCounters(['date' => 86400 * $id]);
            return $this->redirect(['schedule/index']);
        }

        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->limit(1)
            ->one();

        $this->view->title = Yii::t('backend', 'controllers.schedule.index.title');
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'schedule' => $schedule,
        ]);
    }
}
