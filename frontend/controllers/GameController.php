<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Event;
use common\models\db\Game;
use common\models\db\GameComment;
use common\models\db\GameVote;
use common\models\db\Lineup;
use common\models\db\UserBlockType;
use common\models\db\UserRole;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class GameController
 * @package frontend\controllers
 */
class GameController extends AbstractController
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
                    'delete-comment',
                    'vote',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'delete-comment',
                            'vote',
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
    public function actionPreview(int $id): string
    {
        $game = Game::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($game);

        $query = Game::find()
            ->joinWith(['schedule'])
            ->where([
                'home_national_id' => $game->home_national_id,
                'home_team_id' => $game->home_team_id,
                'guest_national_id' => $game->guest_national_id,
                'guest_team_id' => $game->guest_team_id,
            ])
            ->orWhere([
                'home_national_id' => $game->guest_national_id,
                'home_team_id' => $game->guest_team_id,
                'guest_national_id' => $game->home_national_id,
                'guest_team_id' => $game->home_team_id,
            ])
            ->orderBy(['date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $win = $draw = $loose = $pointFor = $pointAgainst = 0;
        foreach ($dataProvider->models as $model) {
            /**
             * @var Game $model
             */
            if (!$model->played) {
                continue;
            }

            if ($game->home_team_id === $model->home_team_id && $game->home_national_id === $model->home_national_id) {
                if ($model->home_point > $model->guest_point) {
                    $win++;
                }
                if ($model->home_point === $model->guest_point) {
                    $draw++;
                }
                if ($model->home_point < $model->guest_point) {
                    $loose++;
                }

                $pointFor += $model->home_point;
                $pointAgainst += $model->guest_point;
            } else {
                if ($model->guest_point > $model->home_point) {
                    $win++;
                }
                if ($model->guest_point === $model->home_point) {
                    $draw++;
                }
                if ($model->guest_point < $model->home_point) {
                    $loose++;
                }

                $pointFor += $model->guest_point;
                $pointAgainst += $model->home_point;
            }
        }

        $this->setSeoTitle('Матч');

        return $this->render('preview', [
            'dataProvider' => $dataProvider,
            'draw' => $draw,
            'game' => $game,
            'loose' => $loose,
            'pointAgainst' => $pointAgainst,
            'pointFor' => $pointFor,
            'win' => $win,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionView(int $id)
    {
        $game = Game::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($game);

        if (!$game->played) {
            return $this->redirect(['preview', 'id' => $id]);
        }

        $model = new GameComment();
        if ($this->user && $model->load(Yii::$app->request->post())) {
            $model->game_id = $id;
            $model->user_id = $this->user->id;
            if ($model->save()) {
                $this->setSuccessFlash('Комментарий успешно сохранён');
                return $this->refresh();
            }
        }

        $lineupGuest = Lineup::find()
            ->where([
                'game_id' => $id,
                'national_id' => $game->guest_national_id,
                'team_id' => $game->guest_team_id,
            ])
            ->orderBy(['position_id' => SORT_ASC])
            ->all();

        $lineupHome = Lineup::find()
            ->where([
                'game_id' => $id,
                'national_id' => $game->home_national_id,
                'team_id' => $game->home_team_id,
            ])
            ->orderBy(['position_id' => SORT_ASC])
            ->all();

        $query = Event::find()
            ->where(['game_id' => $id])
            ->orderBy(['id' => SORT_ASC]);

        $eventDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $query = GameComment::find()
            ->with(['user'])
            ->where(['game_id' => $id])
            ->orderBy(['id' => SORT_ASC]);

        $commentDataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->setSeoTitle('Матч');

        return $this->render('view', [
            'commentDataProvider' => $commentDataProvider,
            'eventDataProvider' => $eventDataProvider,
            'game' => $game,
            'lineupGuest' => $lineupGuest,
            'lineupHome' => $lineupHome,
            'model' => $model,
            'userBlockComment' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one() : null,
            'userBlockGame' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT_GAME)->one() : null,
        ]);
    }

    /**
     * @param int $id
     * @param int $gameId
     * @return Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDeleteComment(int $id, int $gameId): Response
    {
        if (UserRole::ADMIN !== $this->user->user_role_id) {
            $this->forbiddenRole();
        }

        $model = GameComment::find()
            ->where(['id' => $id, 'game_id' => $gameId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        try {
            $model->delete();
            $this->setSuccessFlash('Комментарий успешно удалён.');
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['view', 'id' => $gameId]);
    }

    /**
     * @param $id
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionVote(int $id): Response
    {
        $game = Game::find()
            ->where(['id' => $id])
            ->andWhere(['not', ['played' => null]])
            ->limit(1)
            ->one();
        $this->notFound($game);

        $vote = (int)Yii::$app->request->get('vote', 1);
        if (!in_array($vote, [-1, 1], true)) {
            $vote = 1;
        }

        $model = GameVote::find()
            ->where(['game_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new GameVote();
            $model->game_id = $id;
            $model->user_id = $this->user->id;
        }
        $model->rating = $vote;
        $model->save();

        return $this->redirect(['view', 'id' => $id]);
    }
}
