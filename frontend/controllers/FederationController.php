<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Country;
use common\models\db\Federation;
use common\models\db\Finance;
use common\models\db\LeagueDistribution;
use common\models\db\National;
use common\models\db\NationalType;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\ParticipantLeague;
use common\models\db\Recommendation;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\User;
use common\models\db\UserRole;
use common\models\db\Vote;
use common\models\db\VoteStatus;
use frontend\models\preparers\TeamPrepare;
use frontend\models\queries\FederationQuery;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class FederationController
 * @package frontend\controllers
 */
class FederationController extends AbstractController
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
                    'news-create',
                    'news-update',
                    'news-delete',
                    'poll-create',
                    'delete-news-comment',
                    'support-admin',
                    'support-admin-load',
                    'money-transfer',
                    'free-team',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'attitude-president',
                            'fire',
                            'news-create',
                            'news-update',
                            'news-delete',
                            'poll-create',
                            'delete-news-comment',
                            'support-admin',
                            'support-admin-load',
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
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTeam(int $id): string
    {
        $federation = $this->getFederation($id);
        $dataProvider = TeamPrepare::getFederationTeamDataProvider($federation->country_id);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.team.title'));
        return $this->render('team', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionNational(int $id): string
    {
        $federation = $this->getFederation($id);

        $query = National::find()
            ->where(['federation_id' => $id, 'national_type_id' => NationalType::MAIN])
            ->orderBy(['national_type_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.national.title'));
        return $this->render('national', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionFinance(int $id): string
    {
        $federation = $this->getFederation($id);

        $seasonId = Yii::$app->request->get('season_id', $this->season->id);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Finance::find()
                ->where(['federation_id' => $id])
                ->andWhere(['season_id' => $seasonId])
                ->orderBy(['id' => SORT_DESC]),
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.finance.title'));

        return $this->render('finance', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionNews(int $id = 0)
    {
        if (!$id) {
            $id = Country::DEFAULT_ID;
            if ($this->myTeamOrVice) {
                $id = $this->myTeamOrVice->stadium->city->country->federation->id;
            }
            return $this->redirect(['news', 'id' => $id]);
        }

        $federation = $this->getFederation($id);

        $query = News::find()
            ->where(['federation_id' => $id])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNews'],
            ],
        ]);

        if ($this->myTeam && $this->myTeam->stadium->city->country->federation->id === $id) {
            $lastNewsId = News::find()
                ->select(['id'])
                ->where(['federation_id' => $id])
                ->orderBy(['id' => SORT_DESC])
                ->scalar();
            if ($lastNewsId) {
                $this->myTeam->federation_news_id = $lastNewsId;
                $this->myTeam->save(true, ['federation_news_id']);
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news.title'));
        return $this->render('news', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionNewsCreate(int $id)
    {
        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.news-create.error'));
            return $this->redirect(['news', 'id' => $id]);
        }

        $model = new News();
        $model->federation_id = $id;
        $model->user_id = $this->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-create.success'));
            return $this->redirect(['news', 'id' => $id]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news-create.title'));

        return $this->render('news-create', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $newsId
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionNewsUpdate(int $id, int $newsId)
    {
        $federation = $this->getFederation($id);

        $model = News::find()
            ->where(['id' => $newsId, 'federation_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-update.success'));
            return $this->redirect(['news', 'id' => $id]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news-update.title'));
        return $this->render('news-update', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $newsId
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionNewsDelete(int $id, int $newsId): Response
    {
        $model = News::find()
            ->where(['id' => $newsId, 'federation_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $model->delete();

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-delete.title'));
        return $this->redirect(['news', 'id' => $id]);
    }

    /**
     * @param int $id
     * @param int $newsId
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionNewsView(int $id, int $newsId)
    {
        $federation = $this->getFederation($id);

        $news = News::find()->where(['id' => $newsId, 'federation_id' => $id])->limit(1)->one();
        $this->notFound($news);

        $model = new NewsComment();
        $model->news_id = $newsId;
        $model->user_id = $this->user->id;
        if ($model->addComment()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-view.success'));
            return $this->refresh();
        }

        $query = NewsComment::find()
            ->where(['news_id' => $newsId])
            ->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNewsComment'],
            ],
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news-view.title'));
        return $this->render('news-view', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
            'model' => $model,
            'news' => $news,
        ]);
    }

    /**
     * @param $id
     * @param $newsId
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDeleteNewsComment($id, $newsId)
    {
        if (UserRole::ADMIN !== $this->user->user_role_id) {
            $this->forbiddenRole();
        }

        /**
         * @var NewsComment $model
         */
        $model = NewsComment::find()
            ->where(['id' => $id, 'news_id' => $newsId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $news = $model->news;

        try {
            $model->delete();
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.delete-news-comment.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['news-view', 'id' => $news->federation_id, 'newsId' => $newsId]);
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
     * @throws Throwable
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

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionVoteCreate($id)
    {
        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.vote-create.error'));
            return $this->redirect(['vote', 'id' => $id]);
        }

        $model = new Vote();
        $model->federation_id = $id;
        $model->vote_status_id = VoteStatus::NEW;
        $model->user_id = $this->user->id;

        if ($model->saveVote()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.vote-create.success'));
            return $this->redirect(['vote', 'id' => $id]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.vote-create.title'));

        return $this->render('vote-create', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $voteId
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionVoteDelete($id, $voteId)
    {
        $model = Vote::find()
            ->where(['id' => $voteId, 'federation_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $model->delete();

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.vote-delete.success'));
        return $this->redirect(['vote', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVote(int $id): string
    {
        $statusArray = [VoteStatus::OPEN, VoteStatus::CLOSE];

        $federation = $this->getFederation($id);
        if ($this->user && in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $statusArray[] = VoteStatus::NEW;
        }

        $query = Vote::find()
            ->where(['vote_status_id' => $statusArray, 'federation_id' => $id])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeVote'],
            ],
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.vote.title'));

        return $this->render('vote', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionLeague(int $id): string
    {
        $federation = $this->getFederation($id);

        $leagueDistribution = LeagueDistribution::find()
            ->where(['federation_id' => $id])
            ->orderBy(['season_id' => SORT_DESC])
            ->limit(1)
            ->one();

        $teamArray = [];

        /**
         * @var ParticipantLeague[] $seasonArray
         */
        $seasonArray = ParticipantLeague::find()
            ->joinWith(['team.stadium.city.country.federation'])
            ->where(['federation.id' => $id])
            ->groupBy(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        foreach ($seasonArray as $season) {
            $teamArray[$season->season_id] = ParticipantLeague::find()
                ->joinWith(['team.stadium.city.country.federation', 'leagueCoefficient'])
                ->where(['federation.id' => $id, 'participant_league.season_id' => $season->season_id])
                ->orderBy(['league_coefficient.point' => SORT_DESC, 'participant_league.stage_in_id' => SORT_DESC])
                ->all();
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.league.title'));
        return $this->render('league', [
            'federation' => $federation,
            'leagueDistribution' => $leagueDistribution,
            'teamArray' => $teamArray,
        ]);
    }

    /**
     * @param int $id
     * @return Federation
     * @throws NotFoundHttpException
     */
    private function getFederation(int $id): Federation
    {
        $federation = FederationQuery::getFederationById($id);
        $this->notFound($federation);

        return $federation;
    }
}
