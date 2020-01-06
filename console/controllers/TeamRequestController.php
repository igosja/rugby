<?php

namespace console\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Site;
use common\models\db\TeamRequest;
use common\models\executors\TeamRequestHandleExecute;
use console\components\AbstractController;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class TeamRequestController
 * @package console\controllers
 */
class TeamRequestController extends AbstractController
{
    /**
     * @return bool
     * @throws Exception
     */
    public function actionIndex()
    {
        if (!Site::status()) {
            return true;
        }

        $this->recommendation();
        $this->queue();

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function recommendation()
    {
        /**
         * @var TeamRequest $teamRequest
         */
        $teamRequest = TeamRequest::find()
            ->joinWith(['recommendation'])
            ->where(['!=', 'recommendation_user_id', 0])
            ->orderBy(['team_request_date' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$teamRequest) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            (new TeamRequestHandleExecute($teamRequest))->execute();
        } catch (Throwable $e) {
            $transaction->rollBack();
            ErrorHelper::log($e);
            return false;
        }
        $transaction->commit();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function queue()
    {
        $expression = new Expression('UNIX_TIMESTAMP()-IFNULL(`count_history`, 0)*46800');
        /**
         * @var TeamRequest $teamRequest
         */
        $teamRequest = TeamRequest::find()
            ->leftJoin(
                [
                    't1' => '(' . (new Query())
                            ->select(['count_history' => 'COUNT(history_id)', 'history_user_id'])
                            ->from(History::tableName())
                            ->where(['history_history_text_id' => HistoryText::USER_MANAGER_TEAM_IN])
                            ->andWhere(['history_user_id' => TeamRequest::find()->select(['team_request_user_id'])])
                            ->groupBy(['history_user_id'])
                            ->createCommand()
                            ->rawSql . ')'
                ],
                'team_request_user_id=history_user_id'
            )
            ->where(['<', 'team_request_date', $expression])
            ->orderBy(['IFNULL(`count_history`, 0)' => SORT_ASC, 'team_request_date' => SORT_ASC])
            ->limit(1)
            ->one();
        if (!$teamRequest) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            (new TeamRequestHandleExecute($teamRequest))->execute();
        } catch (Throwable $e) {
            $transaction->rollBack();
            ErrorHelper::log($e);
            return false;
        }
        $transaction->commit();
        return true;
    }
}
