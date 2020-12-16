<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\components\helpers\TimeZoneHelper;
use common\models\db\Achievement;
use common\models\db\Blacklist;
use common\models\db\Country;
use common\models\db\Federation;
use common\models\db\Finance;
use common\models\db\History;
use common\models\db\Loan;
use common\models\db\Logo;
use common\models\db\National;
use common\models\db\Season;
use common\models\db\Sex;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\User;
use common\models\db\UserHoliday;
use common\models\db\UserRating;
use common\models\executors\TeamManagerFireExecute;
use frontend\models\forms\ChangePassword;
use frontend\models\forms\Holiday;
use frontend\models\forms\UserLogo;
use frontend\models\forms\UserTransferFinance;
use Throwable;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends AbstractController
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
                    'drop-team',
                    'questionnaire',
                    'holiday',
                    'password',
                    'money-transfer',
                    'referral',
                    'delete',
                    'notes',
                    'black-list',
                    'logo',
                    'social',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'drop-team',
                            'questionnaire',
                            'holiday',
                            'password',
                            'money-transfer',
                            'delete',
                            'referral',
                            'notes',
                            'black-list',
                            'logo',
                            'social',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     */
    public function actionView(int $id = 0)
    {
        if (!$id) {
            $id = $this->user->id ?? User::ADMIN_USER_ID;

            return $this->redirect(['user/view', 'id' => $id]);
        }

        $query = Federation::find()
            ->joinWith(['country'])
            ->where(['president_user_id' => $id])
            ->orWhere(['vice_user_id' => $id])
            ->orderBy(['name' => SORT_ASC]);
        $countryDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query = Team::find()
            ->where(['or', ['user_id' => $id], ['vice_user_id' => $id]]);
        $teamDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query = National::find()
            ->where(['or', ['user_id' => $id], ['vice_user_id' => $id]]);
        $nationalDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $userRating = UserRating::find()
            ->where(['user_id' => $id, 'season_id' => null])
            ->one();
        if (!$userRating) {
            $userRating = new UserRating();
            $userRating->user_id = $id;
            $userRating->save();
        }

        $query = UserRating::find()
            ->where(['user_id' => $id])
            ->andWhere(['not', ['season_id' => null]])
            ->orderBy(['season_id' => SORT_DESC]);
        $ratingDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query = History::find()
            ->where(['user_id' => $id])
            ->orderBy(['id' => SORT_DESC]);
        $historyDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSeoTitle('Профиль менеджера');

        return $this->render('view', [
            'countryDataProvider' => $countryDataProvider,
            'historyDataProvider' => $historyDataProvider,
            'nationalDataProvider' => $nationalDataProvider,
            'ratingDataProvider' => $ratingDataProvider,
            'teamDataProvider' => $teamDataProvider,
            'userRating' => $userRating,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionAchievement(int $id): string
    {
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Achievement::find()
                ->where(['user_id' => $id])
                ->orderBy(['id' => SORT_DESC]),
        ]);

        $this->setSeoTitle('Достижения менеджера');

        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionTrophy(int $id): string
    {
        $query = Achievement::find()
            ->where(['user_id' => $id, 'place' => [null, 1], 'stage_id' => null])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle('Трофеи менеджера');

        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionFinance(int $id): string
    {
        $seasonId = Yii::$app->request->get('season_id', $this->season->id);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Finance::find()
                ->where(['user_id' => $id])
                ->andWhere(['season_id' => $seasonId])
                ->orderBy(['id' => SORT_DESC]),
        ]);

        $this->setSeoTitle('Финансы менеджера');

        return $this->render('finance', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionDeal($id)
    {
        $dataProviderTransferFrom = new ActiveDataProvider([
            'pagination' => false,
            'query' => Transfer::find()
                ->where(['user_seller_id' => $id])
                ->andWhere(['not', ['ready' => null]])
                ->orderBy(['ready' => SORT_DESC]),
        ]);
        $dataProviderTransferTo = new ActiveDataProvider([
            'pagination' => false,
            'query' => Transfer::find()
                ->where(['user_buyer_id' => $id])
                ->andWhere(['not', ['ready' => null]])
                ->orderBy(['ready' => SORT_DESC]),
        ]);
        $dataProviderLoanFrom = new ActiveDataProvider([
            'pagination' => false,
            'query' => Loan::find()
                ->where(['user_seller_id' => $id])
                ->andWhere(['not', ['ready' => null]])
                ->orderBy(['ready' => SORT_DESC]),
        ]);
        $dataProviderLoanTo = new ActiveDataProvider([
            'pagination' => false,
            'query' => Loan::find()
                ->where(['user_buyer_id' => $id])
                ->andWhere(['not', ['ready' => null]])
                ->orderBy(['ready' => SORT_DESC]),
        ]);

        $this->setSeoTitle('Сделки менеджера');

        return $this->render('deal', [
            'dataProviderTransferFrom' => $dataProviderTransferFrom,
            'dataProviderTransferTo' => $dataProviderTransferTo,
            'dataProviderLoanFrom' => $dataProviderLoanFrom,
            'dataProviderLoanTo' => $dataProviderLoanTo,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionQuestionnaire()
    {
        /**
         * @var User $model
         */
        $model = Yii::$app->user->identity;
        if ($model->updateQuestionnaire()) {
            $this->setSuccessFlash('Данные успешно сохранены.');
            return $this->refresh();
        }

        $dayArray = [];
        for ($i = 1; $i <= 31; $i++) {
            $dayArray[$i] = $i;
        }

        $monthArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthArray[$i] = $i;
        }

        $currentYear = date('Y');
        $firstYear = $currentYear - 100;
        $yearArray = [];
        for ($i = $currentYear; $i >= $firstYear; $i--) {
            $yearArray[$i] = $i;
        }

        $this->setSeoTitle('Анкета менеджера');

        return $this->render('questionnaire', [
            'countryArray' => Country::selectOptions(),
            'dayArray' => $dayArray,
            'model' => $model,
            'monthArray' => $monthArray,
            'sexArray' => Sex::selectOptions(),
            'timeZoneArray' => TimeZoneHelper::zoneList(),
            'yearArray' => $yearArray,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionHoliday()
    {
        $model = new Holiday();
        $model->setUser($this->user);
        if ($model->change()) {
            $this->setSuccessFlash('Данные успешно сохранены.');
            return $this->refresh();
        }

        $teamArray = [];
        foreach ($this->myTeamArray as $myTeam) {
            $userArray = User::find()
                ->where(['>', 'date_login', time() - 604800])
                ->andWhere(['!=', 'id', $this->user->id])
                ->andWhere(['is_no_vice' => false])
                ->andWhere([
                    'not',
                    [
                        'id' => Team::find()
                            ->joinWith(['stadium.city.country'])
                            ->select(['user_id'])
                            ->where(['country.id' => $myTeam->stadium->city->country->id])
                    ]
                ])
                ->andWhere([
                    'not',
                    [
                        'id' => Team::find()
                            ->joinWith(['stadium.city.country'])
                            ->select(['vice_user_id'])
                            ->where(['country.id' => $myTeam->stadium->city->country->id])
                            ->andWhere(['!=', 'team.id', $myTeam->id])
                    ]
                ])
                ->orderBy(['login' => SORT_ASC])
                ->all();
            $teamArray[] = [
                'team' => $myTeam,
                'userArray' => ArrayHelper::map($userArray, 'id', 'login'),
            ];
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => UserHoliday::find()
                ->where(['user_id' => $this->user->id])
                ->orderBy(['id' => SORT_DESC]),
        ]);

        $this->setSeoTitle('Отпуск менеджера');
        return $this->render('holiday', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'teamArray' => $teamArray,
        ]);
    }

    /**
     * @return array|string|Response
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionPassword()
    {
        $model = new ChangePassword();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->change()) {
            $this->setSuccessFlash('Пароль успешно изменён.');
            return $this->refresh();
        }

        $this->setSeoTitle('Смена пароля');
        return $this->render('password', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionMoneyTransfer()
    {
        $model = new UserTransferFinance(['user' => $this->user]);
        if ($model->execute()) {
            $this->setSuccessFlash('Деньги успешно переведены');
            return $this->refresh();
        }

        $federationArray = Federation::find()
            ->joinWith(['country'])
            ->where(['!=', 'federation.id', 0])
            ->orderBy(['country.name' => SORT_ASC])
            ->all();

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

        $this->setSeoTitle('Перевод денег с личного счета');

        return $this->render('money-transfer', [
            'federationArray' => ArrayHelper::map($federationArray, 'id', 'country.name'),
            'model' => $model,
            'teamArray' => $teamItems,
        ]);
    }

    /**
     * @return string
     */
    public function actionReferral(): string
    {
        $query = User::find()
            ->where(['referrer_user_id' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle('Партнёрская программа');

        return $this->render('referral', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|Response
     * @throws Throwable
     */
    public function actionDropTeam()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                (new TeamManagerFireExecute($this->myTeam))->execute();
                $this->setSuccessFlash();
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }

            return $this->redirect(['drop-team']);
        }

        $this->setSeoTitle('Отказ от команды');

        return $this->render('drop-team', [
            'team' => $this->myTeam,
        ]);
    }

    /**
     * @return string
     */
    public function actionReRegister()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                $result = $this->myTeam->reRegister();
                if ($result['status']) {
                    $this->setSuccessFlash($result['message']);
                } else {
                    $this->setErrorFlash($result['message']);
                }
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }

            return $this->redirect(['user/re-register']);
        }

        $this->setSeoTitle('Преререгистрация команды');
        return $this->render('re-register', [
            'team' => $this->myTeam,
        ]);
    }

    /**
     * @return string|Response
     * @throws Throwable
     */
    public function actionDelete()
    {
        if (Yii::$app->request->get('ok')) {
            try {
                $this->user->date_delete = time();
                $this->user->save(true, ['date_delete']);

                foreach ($this->myTeamArray as $team) {
                    (new TeamManagerFireExecute($team))->execute();
                }

                $this->setSuccessFlash();
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }

            return $this->redirect(['delete']);
        }

        $this->setSeoTitle('Удаление аккаунта');
        return $this->render('delete');
    }

    /**
     * @return string|Response
     */
    public function actionRestore()
    {
        if (Yii::$app->request->get('ok')) {
            try {
                $this->user->date_delete = 0;
                $this->user->save(true, ['date_delete']);

                $this->setSuccessFlash();
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }

            return $this->redirect(['view', 'id' => $this->user->id]);
        }

        $this->setSeoTitle('Восстановление аккаунта');
        return $this->render('restore');
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionNotes()
    {
        $model = $this->user;

        if ($model->updateNotes()) {
            $this->setSuccessFlash('Данные успешно сохранены.');
            return $this->refresh();
        }

        $this->setSeoTitle('Блокнот менеджера');

        return $this->render('notes', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionBlacklist(int $id): Response
    {
        $blacklist = Blacklist::find()
            ->where([
                'owner_user_id' => $this->user->id,
                'blocked_user_id' => $id,
            ])
            ->limit(1)
            ->one();
        if ($blacklist) {
            $blacklist->delete();
        } else {
            $model = new Blacklist();
            $model->owner_user_id = $this->user->id;
            $model->blocked_user_id = $id;
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['user/view', 'id' => $id]);
    }

    /**
     * @return string|Response
     * @throws \Exception
     */
    public function actionLogo()
    {
        $user = $this->user;

        $model = new UserLogo(['userId' => $user->id]);
        if ($model->upload()) {
            $this->setSuccessFlash();
            return $this->refresh();
        }

        $logoArray = Logo::find()
            ->where(['team_id' => null])
            ->all();

        $this->setSeoTitle(Html::encode($user->login) . '. Загрузка фото');

        return $this->render('logo', [
            'logoArray' => $logoArray,
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionSocial()
    {
        $model = $this->user;

        $this->setSeoTitle(Html::encode($model->login) . '. Профили в социальных сетях.');

        return $this->render('social', [
            'model' => $model,
        ]);
    }
}
