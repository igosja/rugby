<?php

// TODO refactor

namespace console\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\TeamRequest;
use common\models\executors\TeamRequestHandleExecute;
use common\models\queries\SiteQuery;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;

/**
 * Processes the team-request queue.
 *
 * Class TeamRequestController
 * @package console\controllers
 */
class TeamRequestController extends AbstractController
{
    /**
     * Processes the team-request queue.
     *
     * @return bool
     * @throws Exception
     */
    public function actionIndex(): bool
    {
        if (!SiteQuery::getStatus()) {
            return true;
        }

        $this->queue();

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function recommendation(): bool
    {
        /**
         * @var TeamRequest $teamRequest
         */
        $teamRequest = TeamRequest::find()
            ->joinWith(['recommendation'])
            ->andWhere(['!=', 'recommendation.user_id', 0])
            ->andWhere(['!=', 'recommendation.user_id', 0])
            ->orderBy(['date' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$teamRequest) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            (new TeamRequestHandleExecute($teamRequest))->execute();
        } catch (Throwable $e) {
            if ($transaction) {
                $transaction->rollBack();
            }
            ErrorHelper::log($e);
            return false;
        }
        if ($transaction) {
            $transaction->commit();
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function queue(): bool
    {
        $expression = new Expression('UNIX_TIMESTAMP()-IFNULL(`count_history`, 0)*46800');
        /**
         * @var TeamRequest $teamRequest
         */
        $teamRequest = TeamRequest::find()
            ->leftJoin(
                [
                    't1' => '(' . (new Query())
                            ->select(['count_history' => 'COUNT(id)', 'user_id'])
                            ->from(History::tableName())
                            ->where(['history_text_id' => HistoryText::USER_MANAGER_TEAM_IN])
                            ->andWhere(['user_id' => TeamRequest::find()->select(['user_id'])])
                            ->groupBy(['user_id'])
                            ->createCommand()
                            ->rawSql . ')'
                ],
                'team_request.user_id=t1.user_id'
            )
            ->where(['<', 'team_request.date', $expression])
            ->orderBy(['IFNULL(`count_history`, 0)' => SORT_ASC, 'team_request.date' => SORT_ASC])
            ->limit(1)
            ->one();
        if (!$teamRequest) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            (new TeamRequestHandleExecute($teamRequest))->execute();
        } catch (Throwable $e) {
            if ($transaction) {
                $transaction->rollBack();
            }
            ErrorHelper::log($e);
            return false;
        }
        if ($transaction) {
            $transaction->commit();
        }
        return true;
    }
}
