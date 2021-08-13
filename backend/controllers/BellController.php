<?php

// TODO refactor

namespace backend\controllers;

use backend\models\queries\ComplaintQuery;
use backend\models\queries\LogoQuery;
use backend\models\queries\SupportQuery;
use backend\models\queries\TeamQuery;
use backend\models\queries\VoteQuery;
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

        $complaint = ComplaintQuery::countNew();
        $freeTeam = TeamQuery::countFreeTeam();
        $logo = LogoQuery::countNewTeamLogo();
        $photo = LogoQuery::countNewUserPhoto();
        $vote = VoteQuery::countNew();
        $support = SupportQuery::countNewQuestions();

        $bell = $support + $vote + $logo + $photo + $complaint;

        $variables = ['bell', 'complaint', 'logo', 'photo', 'support', 'vote'];
        foreach ($variables as $variable) {
            if (!$$variable) {
                $$variable = '';
            }
        }

        return [
            'bell' => $bell,
            'complaint' => $complaint,
            'freeTeam' => $freeTeam,
            'logo' => $logo,
            'photo' => $photo,
            'vote' => $vote,
            'support' => $support,
        ];
    }
}
