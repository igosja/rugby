<?php

namespace frontend\controllers;

use common\components\AbstractWebController;
use common\models\db\National;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\User;
use common\models\queries\SiteQuery;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class AbstractController
 * @package frontend\components
 *
 * @property National $myNational
 * @property National $myNationalOrVice
 * @property National $myNationalVice
 * @property Team[] $myOwnTeamArray
 * @property Team $myTeam
 * @property Team[] $myTeamArray
 * @property Team $myTeamOrVice
 * @property Team $myTeamVice
 * @property Season $season
 * @property User $user
 */
abstract class AbstractController extends AbstractWebController
{
    /**
     * @var National $myNational
     */
    public $myNational;

    /**
     * @var National $myNationalOrVice
     */
    public $myNationalOrVice;

    /**
     * @var National $myNationalVice
     */
    public $myNationalVice;

    /**
     * @var Team[] $myOwnTeamArray
     */
    public $myOwnTeamArray = [];

    /**
     * @var Team $myTeam
     */
    public $myTeam;

    /**
     * @var Team[] $myTeamArray
     */
    public $myTeamArray = [];

    /**
     * @var Team $myTeamOrVice
     */
    public $myTeamOrVice;

    /**
     * @var Team $myTeamVice
     */
    public $myTeamVice;

    /**
     * @var Season $season
     */
    public $season;

    /**
     * @var User $user
     */
    public $user;

    /**
     * @param Action $action
     * @return bool|Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

//        $allowedIp = [
//            '127.0.0.1',
//        ];

//        $userIp = Yii::$app->request->headers->get('x-real-ip');
//        if (!$userIp) {
//            $userIp = Yii::$app->request->userIP;
//        }

        $this->season = Season::find()
            ->select(['id'])
            ->andWhere(['is_future' => false])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity;

            User::updateAll(['date_login' => time()], ['id' => $this->user->id]);
//            if ($userIp && (User::ADMIN_USER_ID == $this->user->user_id || !in_array($userIp, $allowedIp))) {
//                $this->user->user_ip = $userIp;
//            }

//            if ($this->user->user_date_block > time() && !($action instanceof ErrorAction) && !($action->controller instanceof SupportController) && !($action->controller instanceof SiteController)) {
//                throw new ForbiddenHttpException(
//                    'Вам заблокирован доступ к сайту.
//                    Причина блокировки - ' . $this->user->reasonBlock->block_reason_text
//                );
//            }

            if (!$this->user->date_confirm) {
                Yii::$app->session->setFlash('warning', 'Пожалуйста, подтвердите свой почтовый адрес');
            }

            if (!('restore' === $action->id && 'user' === $action->controller->id) && $this->user->date_delete) {
                return $this->redirect(['user/restore']);
            }

            $this->checkSessionMyTeamId();
            $mySessionTeamId = Yii::$app->session->get('myTeamId');

            /**
             * @var Team[] $teamUserArray
             */
            $teamUserArray = Team::find()
                ->select(
                    [
                        'base_scout_id',
                        'id',
                        'name',
                        'stadium_id',
                        'user_id',
                        'vice_user_id',
                    ]
                )
                ->where(
                    [
                        'or',
                        ['user_id' => $this->user->id],
                        ['vice_user_id' => $this->user->id],
                    ]
                )
                ->andWhere(['!=', 'id', 0])
                ->all();

            $teamArray = [];
            $teamViceArray = [];
            foreach ($teamUserArray as $team) {
                if ($this->user->id === $team->user_id) {
                    $teamArray[$team->id] = $team;
                    if ($mySessionTeamId === $team->id) {
                        $this->myTeam = $team;
                    }
                    if (!$mySessionTeamId && !$this->myTeam) {
                        $this->myTeam = $team;
                    }
                }
                if ($this->user->id === $team->vice_user_id) {
                    $teamViceArray[$team->id] = $team;
                    if ($mySessionTeamId === $team->id) {
                        $this->myTeamVice = $team;
                    }
                }
            }

            $this->myTeamArray = ArrayHelper::merge($teamArray, $teamViceArray);
            $this->myOwnTeamArray = $teamArray;
            $this->myTeamOrVice = $this->myTeam ?: $this->myTeamVice;

            /**
             * @var National[] $nationalUserArray
             */
            $nationalUserArray = National::find()
                ->select(['id'])
                ->where(
                    [
                        'or',
                        ['user_id' => $this->user->id],
                        ['vice_user_id' => $this->user->id],
                    ]
                )
                ->all();
            foreach ($nationalUserArray as $national) {
                if ($this->user->id === $national->user_id) {
                    $this->myNational = $national;
                }
                if ($this->user->id === $national->vice_user_id) {
                    $this->myNationalVice = $national;
                }
            }

            $this->myNationalOrVice = $this->myNational ?: $this->myNationalVice;
        }

        $siteStatus = SiteQuery::getStatus();
        if (!$siteStatus && !('site' === $action->controller->id && 'closed' === $action->id)) {
            return $this->redirect(['site/closed']);
        }

        if ($siteStatus && 'site' === $action->controller->id && 'closed' === $action->id) {
            return $this->redirect(['site/index']);
        }

        return true;
    }

    /**
     * @return void
     */
    private function checkSessionMyTeamId(): void
    {
        $session = Yii::$app->session;
        if ($session->get('myTeamId') && !array_key_exists($session->get('myTeamId'), $this->myTeamArray)) {
            $session->remove('myTeamId');
        }
    }

    /**
     * @param $text
     * @return void
     */
    public function seoTitle($text): void
    {
        $this->view->title = $text;
        $this->seoDescription();
    }

    /**
     * @return void
     */
    private function seoDescription(): void
    {
        $this->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => $this->view->title . ' на сайте Виртуальной Регбийной Лиги'
            ]
        );
    }

    /**
     * @throws ForbiddenHttpException
     */
    protected function forbiddenRole(): void
    {
        throw new ForbiddenHttpException('Не хватает прав для выполнения этой операции');
    }
}
