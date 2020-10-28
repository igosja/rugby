<?php

namespace backend\controllers;

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
    public function actionIndex(): array
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

        if (!$bell) {
            $bell = '';
        }

        if (!$complaint) {
            $complaint = '';
        }

        if (!$logo) {
            $logo = '';
        }

        if (!$photo) {
            $photo = '';
        }

        if (!$poll) {
            $poll = '';
        }

        if (!$support) {
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
