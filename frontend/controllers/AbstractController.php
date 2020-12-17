<?php

// TODO refactor

namespace frontend\controllers;

use common\components\AbstractWebController;
use common\models\db\National;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use common\models\queries\SiteQuery;
use frontend\models\queries\NationalQuery;
use frontend\models\queries\TeamQuery;
use Yii;
use yii\base\Action;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ErrorAction;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class AbstractController
 * @package frontend\components
 *
 * @property-read array $dropDownItems
 *
 * @property-write string $seoTitle
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
     * @var National|null $myNational
     */
    public ?National $myNational = null;

    /**
     * @var National|null $myNationalOrVice
     */
    public ?National $myNationalOrVice = null;

    /**
     * @var National|null $myNationalVice
     */
    public ?National $myNationalVice = null;

    /**
     * @var Team[] $myOwnTeamArray
     */
    public array $myOwnTeamArray = [];

    /**
     * @var Team|null $myTeam
     */
    public ?Team $myTeam = null;

    /**
     * @var Team[] $myTeamArray
     */
    public array $myTeamArray = [];

    /**
     * @var Team|null $myTeamOrVice
     */
    public ?Team $myTeamOrVice = null;

    /**
     * @var Team|null $myTeamVice
     */
    public ?Team $myTeamVice = null;

    /**
     * @var Season|null $season
     */
    public ?Season $season;

    /**
     * @var User|null $user
     */
    public ?User $user = null;

    /**
     * @param Action $action
     * @return bool|Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if ($redirect = $this->redirectBySiteStatus($action)) {
            return $redirect;
        }

        $this->loadCurrentSeason();

        if (!Yii::$app->user->isGuest) {
            if (!('restore' === $action->id && 'user' === $action->controller->id) && $this->user->date_delete) {
                return $this->redirect(['/user/restore']);
            }

            $this->updateLastLogin();
            $this->checkUserBlock($action);
            $this->checkEmailConfirmation();
            $this->loadMyTeamArray();
            $this->checkSessionMyTeamId();
            $this->loadTeams();
            $this->loadNationals();
        }

        return true;
    }

    /**
     * @return array
     */
    public function getDropDownItems(): array
    {
        $result = [];
        foreach ($this->myTeamArray as $myTeam) {
            $result[$myTeam->id] = $myTeam->name
                . ' ('
                . $myTeam->stadium->city->country->name
                . ($myTeam->vice_user_id === $this->user->id ? ', зам' : '')
                . ')';
        }
        return $result;
    }

    /**
     * @return Response
     */
    public function redirectToMyTeam(): Response
    {
        return $this->redirect(['team/view', 'id' => $this->myTeam->id]);
    }

    /**
     * @param string $text
     */
    public function setSeoTitle(string $text): void
    {
        $this->view->title = $text;
        $this->setSeoDescription();
    }

    /**
     * @throws ForbiddenHttpException
     */
    protected function forbiddenRole(): void
    {
        throw new ForbiddenHttpException(
            'Не хватает прав для выполнения этой операции'
        );
    }

    private function checkEmailConfirmation(): void
    {
        if (!$this->user->date_confirm) {
            Yii::$app->session->setFlash(
                'warning',
                'Пожалуйста, подтвердите свой почтовый адрес'
            );
        }
    }

    private function checkSessionMyTeamId(): void
    {
        $session = Yii::$app->session;
        if ($session->get('myTeamId') && !array_key_exists($session->get('myTeamId'), $this->myTeamArray)) {
            $session->remove('myTeamId');
        }
    }

    /**
     * @param Action $action
     * @throws ForbiddenHttpException
     */
    private function checkUserBlock(Action $action): void
    {
        if (!($action instanceof ErrorAction)) {
            $classes = [SupportController::class, SiteController::class];
            if (!in_array(get_class($action->controller), $classes)) {
                /**
                 * @var UserBlock $userBlock
                 */
                $userBlock = $this
                    ->user
                    ->getUserBlock(UserBlockType::TYPE_SITE)
                    ->one();
                if ($userBlock && $userBlock->date > time()) {
                    throw new ForbiddenHttpException(
                        'Вам заблокирован доступ к сайту. Причина блокировки - '
                        . $userBlock->userBlockReason->text
                    );
                }
            }
        }
    }

    private function loadCurrentSeason(): void
    {
        $this->season = Season::find()
            ->andWhere(['is_future' => false])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    private function loadMyTeamArray(): void
    {
        $this->myTeamArray = TeamQuery::getTeamListByUserId($this->user->id);
    }

    private function loadNationals(): void
    {
        /**
         * @var National[] $nationalUserArray
         */
        $nationalUserArray = NationalQuery::getNationalListByUserId($this->user->id);
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

    private function loadTeams(): void
    {
        $mySessionTeamId = (int)Yii::$app->session->get('myTeamId');

        $teamArray = [];
        $teamViceArray = [];
        foreach ($this->myTeamArray as $team) {
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
    }

    /**
     * @param Action $action
     * @return false|Response
     */
    private function redirectBySiteStatus(Action $action)
    {
        $siteStatus = SiteQuery::getStatus();
        if (!$siteStatus && !('site' === $action->controller->id && 'closed' === $action->id)) {
            return $this->redirect(['site/closed']);
        }

        if ($siteStatus && 'site' === $action->controller->id && 'closed' === $action->id) {
            return $this->redirect(['site/index']);
        }
        return false;
    }

    /**
     * @return void
     */
    private function setSeoDescription(): void
    {
        $this->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => $this->view->title
                    . ' на сайте Виртуальной Регбийной Лиги'
            ]
        );
    }

    /**
     * @throws Exception
     */
    private function updateLastLogin(): void
    {
        $this->user->date_login = time();
        $this->user->save(true, ['date_login']);
    }
}
