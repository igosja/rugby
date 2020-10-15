<?php

namespace backend\controllers;

use backend\components\AbstractController;
use common\models\db\Complaint;
use common\models\db\Logo;
use common\models\db\Support;
use common\models\db\Team;
use common\models\db\Vote;
use common\models\db\VoteStatus;
use Yii;
use yii\web\Response;

/**
 * Class BellController
 * @package backend\controllers
 */
class BellController extends AbstractController
{
    /**
     * @return array
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $complaint = Complaint::find()->where(['complaint_ready' => 0])->count();
        $freeTeam = Team::find()->where(['team_user_id' => 0])->andWhere(['!=', 'team_id', 0])->count();
        $logo = Logo::find()->where(['!=', 'logo_team_id', 0])->count();
        $photo = Logo::find()->where(['logo_team_id' => 0])->count();
        $poll = Vote::find()->where(['poll_poll_status_id' => VoteStatus::NEW_ONE])->count();
        $support = Support::find()->where(['support_question' => 1, 'support_read' => 0, 'support_inside' => 0])->count(
        );

        $bell = $support + $poll + $logo + $photo + $complaint;

        if (0 == $bell) {
            $bell = '';
        }

        if (0 == $complaint) {
            $complaint = '';
        }

        if (0 == $logo) {
            $logo = '';
        }

        if (0 == $photo) {
            $photo = '';
        }

        if (0 == $poll) {
            $poll = '';
        }

        if (0 == $support) {
            $support = '';
        }

        return [
            'bell' => $bell,
            'complaint' => $complaint,
            'freeTeam' => $freeTeam,
            'logo' => $logo,
            'photo' => $photo,
            'poll' => $poll,
            'support' => $support,
        ];
    }
}
