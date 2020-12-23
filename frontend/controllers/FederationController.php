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
use frontend\models\forms\FederationTransferFinance;
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

        $this->setSeoTitle('Команды федерации');
        return $this->render('team', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @return string
     */
    public function actionNational($id)
    {
        $federation = $this->getFederation($id);

        $query = National::find()
            ->where(['federation_id' => $id, 'national_type_id' => NationalType::MAIN])
            ->orderBy(['national_type_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->setSeoTitle('Сборные');
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

        $this->setSeoTitle('Фонд федерации');

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

        $this->setSeoTitle('Новости федерации');
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
            $this->setErrorFlash('Только президент федерации или его заместитель может создавать новости');
            return $this->redirect(['news', 'id' => $id]);
        }

        $model = new News();
        $model->federation_id = $id;
        $model->user_id = $this->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Новость успешно сохранена');
            return $this->redirect(['news', 'id' => $id]);
        }

        $this->setSeoTitle('Создание новости');

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
            $this->setSuccessFlash('Новость успешно сохранена');
            return $this->redirect(['news', 'id' => $id]);
        }

        $this->setSeoTitle('Редактирование новости');
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

        $this->setSuccessFlash('Новость успешно удалена');
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
            $this->setSuccessFlash('Комментарий успешно сохранён');
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

        $this->setSeoTitle('Комментарии к новости');
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
            $this->setSuccessFlash('Комментарий успешно удалён.');
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
            $this->setErrorFlash('Только президент федерации или его заместитель может рекомандовать менеджеров');
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

        $this->setSeoTitle('Свободные команды федерации');

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
            $this->setErrorFlash('Только президент федерации или его заместитель может рекомандовать менеджеров');
            return $this->redirect(['team', 'id' => $id]);
        }

        if ($team->recommendation) {
            $this->setErrorFlash('На получение этой команды уже подана рекомендация');
            return $this->redirect(['free-team', 'id' => $id]);
        }

        $model = new Recommendation();
        $model->team_id = $teamId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Рекомендация успешно сохранена');
            return $this->redirect(['free-team', 'id' => $federation->id]);
        }

        $userArray = User::find()
            ->where(['!=', 'id', 0])
            ->andWhere(['>', 'date_login', time() - 604800])
            ->orderBy(['login' => SORT_ASC])
            ->all();

        $this->setSeoTitle('Создание рекомендации на получение команды');

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
            $this->setErrorFlash('Только президент федерации или его заместитель может рекомандовать менеджеров');
            return $this->redirect(['team', 'id' => $id]);
        }

        if (!$team->recommendation) {
            $this->setErrorFlash('На получение этой команды не подана рекомендация');
            return $this->redirect(['free-team', 'id' => $id]);
        }

        $team->recommendation->delete();
        $this->setSuccessFlash('Рекомендация успешно удалена');

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
            $this->setErrorFlash('Только президент федерации или его заместитель может создавать опросы');
            return $this->redirect(['vote', 'id' => $id]);
        }

        $model = new Vote();
        $model->federation_id = $id;
        $model->vote_status_id = VoteStatus::NEW;
        $model->user_id = $this->user->id;

        if ($model->saveVote()) {
            $this->setSuccessFlash('Опрос успешно сохранён');
            return $this->redirect(['vote', 'id' => $id]);
        }

        $this->setSeoTitle('Создание опроса');

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

        $this->setSuccessFlash('Опрос успешно удалён');
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

        $this->setSeoTitle('Опросы федерации');

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

        $this->setSeoTitle('Лига чемпионов');

        return $this->render('league', [
            'federation' => $federation,
            'leagueDistribution' => $leagueDistribution,
            'teamArray' => $teamArray,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionMoneyTransfer(int $id)
    {
        $federation = $this->getFederation($id);
        if ($this->user->id !== $federation->president_user_id) {
            $this->setErrorFlash('Только президент федерации может распределять фонд федерации');
            return $this->redirect(['country/news', 'id' => $id]);
        }

        $model = new FederationTransferFinance(['federation' => $federation]);
        if ($model->execute()) {
            $this->setSuccessFlash('Деньги успешно переведены');
            return $this->refresh();
        }

        /**
         * @var Team[] $teamArray
         */
        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->orderBy(['name' => SORT_ASC])
            ->all();
        $teamItems = [];

        foreach ($teamArray as $team) {
            $teamItems[$team->id] = $team->fullName();
        }

        $this->setSeoTitle('Распределение фонда федерации');

        return $this->render('money-transfer', [
            'federation' => $federation,
            'model' => $model,
            'teamArray' => $teamItems,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function actionPresidentAttitude(int $id): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['news', 'id' => $id]);
        }

        if (!$this->myTeam->load(Yii::$app->request->post())) {
            return $this->redirect(['news', 'id' => $id]);
        }

        $this->myTeam->save(true, ['president_attitude_id']);
        return $this->redirect(['news', 'id' => $id]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionFire($id)
    {
        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash('Вы не занимаете руководящей должности в этой стране');
            return $this->redirect(['country/news', 'id' => $id]);
        }

        if (!$federation->vice_user_id) {
            $this->setErrorFlash('Нельзя отказаться от должности если в федерации нет заместителя');
            return $this->redirect(['country/news', 'id' => $id]);
        }

        if (Yii::$app->request->get('ok')) {
            if ($this->user->id === $federation->president_user_id) {
                $federation->firePresident();
            } elseif ($this->user->id === $federation->vice_user_id) {
                $federation->fireVicePresident();
            }

            $this->setSuccessFlash('Вы успешно отказались от должности');
            return $this->redirect(['news', 'id' => $id]);
        }

        $this->setSeoTitle('Отказ от должности');
        return $this->render('fire', [
            'id' => $id,
            'federation' => $federation,
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
