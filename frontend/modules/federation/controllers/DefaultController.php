<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\Recommendation;
use common\models\db\Team;
use common\models\db\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DefaultController
 * @package frontend\modules\federation\controllers
 */
class DefaultController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'attitude-president',
                    'fire',
                    'money-transfer',
                    'free-team',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'attitude-president',
                            'fire',
                            'money-transfer',
                            'free-team',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAttitudePresident(int $id): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['/federation/news/index', 'id' => $id]);
        }

        $federation = $this->getFederation($id);
        if ($federation->president_user_id === $this->user->id) {
            return $this->redirect(['/federation/default/news', 'id' => $id]);
        }

        if (!$this->myTeam->load(Yii::$app->request->post())) {
            return $this->redirect(['/federation/default/news', 'id' => $id]);
        }

        $this->myTeam->save(true, ['president_attitude_id']);
        return $this->redirect(['/federation/default/news', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionFreeTeam(int $id)
    {
        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.free-team.error'));
            return $this->redirect(['team', 'id' => $id]);
        }
        $query = Team::find()
            ->joinWith(['stadium.city.country.federation'])
            ->where(['federation.id' => $id, 'team.user_id' => 0]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'team' => [
                        'asc' => ['team.name' => SORT_ASC],
                        'desc' => ['team.name' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['team' => SORT_ASC],
            ]
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.free-team.title'));

        return $this->render('free-team', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @param int $teamId
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionRecommendationCreate(int $id, int $teamId)
    {
        $team = Team::find()
            ->where(['id' => $teamId])
            ->limit(1)
            ->one();
        $this->notFound($team);

        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.recommendation-create.error.role'));
            return $this->redirect(['team', 'id' => $id]);
        }

        if ($team->recommendation) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.recommendation-create.error.recommendation'));
            return $this->redirect(['free-team', 'id' => $id]);
        }

        $model = new Recommendation();
        $model->team_id = $teamId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.recommendation-create.success'));
            return $this->redirect(['free-team', 'id' => $federation->id]);
        }

        $userArray = User::find()
            ->where(['!=', 'id', 0])
            ->andWhere(['>', 'date_login', time() - 604800])
            ->orderBy(['login' => SORT_ASC])
            ->all();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.recommendation-create.title'));

        return $this->render('recommendation-create', [
            'federation' => $federation,
            'userArray' => ArrayHelper::map($userArray, 'id', 'login'),
            'model' => $model,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @param int $teamId
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionRecommendationDelete(int $id, int $teamId): Response
    {
        $team = Team::find()
            ->where(['id' => $teamId])
            ->limit(1)
            ->one();
        $this->notFound($team);

        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.recommendation-delete.error.role'));
            return $this->redirect(['team', 'id' => $id]);
        }

        if (!$team->recommendation) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.recommendation-delete.error.recommendation'));
            return $this->redirect(['free-team', 'id' => $id]);
        }

        $team->recommendation->delete();
        $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.recommendation-delete.success'));

        return $this->redirect(['free-team', 'id' => $id]);
    }
}
