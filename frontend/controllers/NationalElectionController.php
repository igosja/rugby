<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\ElectionNational;
use common\models\db\ElectionNationalApplication;
use common\models\db\ElectionNationalVote;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use common\models\db\National;
use common\models\db\NationalType;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\Position;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class NationalElectionController
 * @package frontend\controllers
 */
class NationalElectionController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionApplication()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionNational) {
            return $this->redirect(['view']);
        }

        $position = National::find()
            ->where(['user_id' => $this->user->id])
            ->count();

        if ($position) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.national-election.application.error.position'));
            return $this->redirect(['team/view']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionNational) {
            $electionNational = new ElectionNational();
            $electionNational->federation_id = $federation->id;
            $electionNational->national_type_id = $national->national_type_id;
            $electionNational->save();
        }

        $model = ElectionNationalApplication::find()
            ->where([
                'election_national_id' => $electionNational->id,
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new ElectionNationalApplication();
            $model->election_national_id = $electionNational->id;
            $model->user_id = $this->user->id;
        }

        if ($model->saveApplication()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.national-election.application.success'));
            return $this->refresh();
        }

        $propArray = $hookerArray = $lockArray = $flankerArray = $eightArray = $scrumHalfArray = $flyHalfArray = $wingArray = $centreArray = $fullBackArray = [];
        $playersData = [
            ['position' => Position::PROP, 'limit' => 30, 'array' => 'propArray'],
            ['position' => Position::HOOKER, 'limit' => 15, 'array' => 'hookerArray'],
            ['position' => Position::LOCK, 'limit' => 30, 'array' => 'lockArray'],
            ['position' => Position::FLANKER, 'limit' => 30, 'array' => 'flankerArray'],
            ['position' => Position::EIGHT, 'limit' => 15, 'array' => 'eightArray'],
            ['position' => Position::SCRUM_HALF, 'limit' => 15, 'array' => 'scrumHalfArray'],
            ['position' => Position::FLY_HALF, 'limit' => 15, 'array' => 'flyHalfArray'],
            ['position' => Position::WING, 'limit' => 30, 'array' => 'wingArray'],
            ['position' => Position::CENTRE, 'limit' => 30, 'array' => 'centreArray'],
            ['position' => Position::FULL_BACK, 'limit' => 15, 'array' => 'fullBackArray'],
        ];

        foreach ($playersData as $playerData) {
            $array = $playerData['array'];
            $$array = Player::find()
                ->where([
                    'country_id' => $national->federation->country_id,
                    'national_id' => [null, $national->id]
                ])
                ->andWhere([
                    'id' => PlayerPosition::find()
                        ->select(['player_id'])
                        ->where(['position_id' => $playerData['position']])
                ])
                ->andWhere(['!=', 'team_id', 0])
                ->orderBy(['power_nominal_s' => SORT_DESC, 'id' => SORT_DESC])
                ->limit($playerData['limit'])
                ->all();
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.national-election.application.title'));
        return $this->render('application', [
            'propArray' => $propArray,
            'hookerArray' => $hookerArray,
            'lockArray' => $lockArray,
            'flankerArray' => $flankerArray,
            'eightArray' => $eightArray,
            'scrumHalfArray' => $scrumHalfArray,
            'flyHalfArray' => $flyHalfArray,
            'wingArray' => $wingArray,
            'centreArray' => $centreArray,
            'fullBackArray' => $fullBackArray,
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param Federation $federation
     * @return National|null
     */
    private function getNational(Federation $federation): ?National
    {
        for ($i = NationalType::MAIN; $i <= NationalType::U19; $i++) {
            $national = National::find()
                ->where(['national_type_id' => $i, 'federation_id' => $federation->id])
                ->limit(1)
                ->one();

            if (!$national->user_id) {
                return $national;
            }
        }

        return null;
    }

    /**
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteApplication(): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionNational) {
            return $this->redirect(['view']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionNational) {
            $electionNational = new ElectionNational();
            $electionNational->federation_id = $federation->id;
            $electionNational->national_type_id = $national->national_type_id;
            $electionNational->save();
        }

        $model = ElectionNationalApplication::find()
            ->where([
                'election_national_id' => $electionNational->id,
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            return $this->redirect(['application']);
        }

        $model->delete();

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.national-election.delete-application.success'));
        return $this->redirect(['application']);
    }

    /**
     * @return string|Response
     */
    public function actionView()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionNational) {
            return $this->redirect(['application']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionNational) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionNationalVote::find()
            ->where([
                'election_national_application_id' => ElectionNationalApplication::find()
                    ->select(['id'])
                    ->where(['election_national_id' => $electionNational->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if (!$voteUser) {
            return $this->redirect(['poll']);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.national-election.view.title'));
        return $this->render('view', [
            'electionNational' => $electionNational,
            'federation' => $federation,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionPoll()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        /**
         * @var National $national
         */
        $national = $this->getNational($federation);

        if ($national->user_id) {
            return $this->redirect(['team/view']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionNational) {
            return $this->redirect(['application']);
        }

        $electionNational = ElectionNational::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionNational) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionNationalVote::find()
            ->where([
                'election_national_application_id' => ElectionNationalApplication::find()
                    ->select(['id'])
                    ->where(['election_national_id' => $electionNational->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if ($voteUser) {
            return $this->redirect(['view']);
        }

        $model = new ElectionNationalVote();
        $model->user_id = $this->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.national-election.poll.success'));
            return $this->refresh();
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.national-election.poll.title'));
        return $this->render('poll', [
            'electionNational' => $electionNational,
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     */
    public function actionPlayer($id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        $electionNationalApplication = ElectionNationalApplication::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();

        if (!$electionNationalApplication) {
            return $this->redirect(['team/view']);
        }

        $electionNational = ElectionNational::find()
            ->where(['id' => $electionNationalApplication->election_national_id])
            ->limit(1)
            ->one();

        if (!$electionNational) {
            return $this->redirect(['team/view']);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.national-election.player.title'));
        return $this->render('player', [
            'electionNationalApplication' => $electionNationalApplication,
            'federation' => $federation,
        ]);
    }
}
