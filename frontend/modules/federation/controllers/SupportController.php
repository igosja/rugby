<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\components\helpers\FormatHelper;
use common\models\db\Support;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class SupportController
 * @package frontend\modules\federation\controllers
 */
class SupportController extends AbstractController
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
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAdmin(int $id)
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.free-team.error'));
            return $this->redirect(['/federation/team/index', 'id' => $id]);
        }

        $model = new Support();
        if ($model->addFederationAdminQuestion($federation->id)) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.support.index.success'));
            return $this->refresh();
        }

        $supportArray = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => false,
            ])
            ->limit(Yii::$app->params['pageSizeMessage'])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $countSupport = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => false,
            ])
            ->count();

        $lazy = 0;
        if ($countSupport > count($supportArray)) {
            $lazy = 1;
        }

        Support::updateAll(
            ['read' => time()],
            ['read' => null, 'federation_id' => $federation->id, 'is_inside' => false, 'is_question' => false]
        );

        $this->setSeoTitle(Yii::t('frontend', 'controllers.support.index.title'));
        return $this->render('admin', [
            'federation' => $federation,
            'id' => $id,
            'lazy' => $lazy,
            'model' => $model,
            'supportArray' => array_reverse($supportArray),
        ]);
    }

    /**
     * @param int $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAdminLoad(int $id): array
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            return [];
        }

        $supportArray = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => false,
            ])
            ->offset(Yii::$app->request->get('offset'))
            ->limit(Yii::$app->request->get('limit'))
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $supportArray = array_reverse($supportArray);

        $countSupport = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => false,
            ])
            ->count();

        if ($countSupport > count($supportArray) + Yii::$app->request->get('offset')) {
            $lazy = 1;
        } else {
            $lazy = 0;
        }

        $list = '';

        foreach ($supportArray as $support) {
            /**
             * @var Support $support
             */
            $list .= '<div class="row margin-top"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-size-3">'
                . FormatHelper::asDateTime($support->date)
                . ', '
                . ($support->is_question ? $support->presidentUser->getUserLink() : $support->adminUser->getUserLink())
                . '</div></div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message '
                . ($support->is_question ? 'message-from-me' : 'message-to-me')
                . '">'
                . nl2br($support->text)
                . '</div></div>';
        }

        $result = [
            'offset' => (int)Yii::$app->request->get('offset') + (int)Yii::$app->request->get('limit'),
            'lazy' => $lazy,
            'list' => $list,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionManager(int $id)
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.free-team.error'));
            return $this->redirect(['/federation/team/index', 'id' => $id]);
        }

        $model = new Support();
        if ($model->addFederationManagerQuestion($federation->id)) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.support.index.success'));
            return $this->refresh();
        }

        $supportArray = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $this->user->id,
            ])
            ->limit(Yii::$app->params['pageSizeMessage'])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $countSupport = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $this->user->id,
            ])
            ->count();

        $lazy = 0;
        if ($countSupport > count($supportArray)) {
            $lazy = 1;
        }

        Support::updateAll(
            ['read' => time()],
            [
                'read' => null,
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $this->user->id,
                'is_question' => false,
            ]
        );

        $this->setSeoTitle(Yii::t('frontend', 'controllers.support.index.title'));
        return $this->render('manager', [
            'federation' => $federation,
            'id' => $id,
            'lazy' => $lazy,
            'model' => $model,
            'supportArray' => array_reverse($supportArray),
        ]);
    }

    /**
     * @param int $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionManagerLoad(int $id): array
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            return [];
        }

        $supportArray = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $this->user->id,
            ])
            ->offset(Yii::$app->request->get('offset'))
            ->limit(Yii::$app->request->get('limit'))
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $supportArray = array_reverse($supportArray);

        $countSupport = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $this->user->id,
            ])
            ->count();

        if ($countSupport > count($supportArray) + Yii::$app->request->get('offset')) {
            $lazy = 1;
        } else {
            $lazy = 0;
        }

        $list = '';

        foreach ($supportArray as $support) {
            /**
             * @var Support $support
             */
            $list .= '<div class="row margin-top"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-size-3">'
                . FormatHelper::asDateTime($support->date)
                . ', '
                . ($support->is_question ? $support->user->getUserLink() : $support->presidentUser->getUserLink())
                . '</div></div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message '
                . ($support->is_question ? 'message-from-me' : 'message-to-me')
                . '">'
                . nl2br($support->text)
                . '</div></div>';
        }

        $result = [
            'offset' => (int)Yii::$app->request->get('offset') + (int)Yii::$app->request->get('limit'),
            'lazy' => $lazy,
            'list' => $list,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUser(int $id)
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.free-team.error'));
            return $this->redirect(['/federation/team/index', 'id' => $id]);
        }

        $query = Support::find()
            ->select([
                'id' => 'MAX(`id`)',
                'federation_id',
                'user_id',
                'read' => 'MIN(`read`)',
            ])
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'is_question' => true,
            ])
            ->groupBy('user_id')
            ->orderBy(['read' => SORT_ASC, 'id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSeoTitle('Техническая поддержка');

        return $this->render('user', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPresident(int $id, int $user_id)
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.free-team.error'));
            return $this->redirect(['/federation/team/index', 'id' => $id]);
        }

        $model = new Support();
        if ($model->addFederationManagerAnswer($federation->id, $user_id)) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.support.index.success'));
            return $this->refresh();
        }

        $supportArray = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $user_id,
            ])
            ->limit(Yii::$app->params['pageSizeMessage'])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $countSupport = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $user_id,
            ])
            ->count();

        $lazy = 0;
        if ($countSupport > count($supportArray)) {
            $lazy = 1;
        }

        Support::updateAll(
            ['read' => time()],
            [
                'read' => null,
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $user_id,
                'is_question' => true,
            ]
        );

        $this->setSeoTitle(Yii::t('frontend', 'controllers.support.index.title'));
        return $this->render('president', [
            'federation' => $federation,
            'id' => $id,
            'lazy' => $lazy,
            'model' => $model,
            'supportArray' => array_reverse($supportArray),
            'user_id' => $user_id,
        ]);
    }

    /**
     * @param int $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPresidentLoad(int $id, int $user_id): array
    {
        $federation = $this->getFederation($id);
        if (!ArrayHelper::isIn($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            return [];
        }

        $supportArray = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $user_id,
            ])
            ->offset(Yii::$app->request->get('offset'))
            ->limit(Yii::$app->request->get('limit'))
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $supportArray = array_reverse($supportArray);

        $countSupport = Support::find()
            ->where([
                'federation_id' => $federation->id,
                'is_inside' => true,
                'user_id' => $user_id,
            ])
            ->count();

        if ($countSupport > count($supportArray) + Yii::$app->request->get('offset')) {
            $lazy = 1;
        } else {
            $lazy = 0;
        }

        $list = '';

        foreach ($supportArray as $support) {
            /**
             * @var Support $support
             */
            $list .= '<div class="row margin-top"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-size-3">'
                . FormatHelper::asDateTime($support->date)
                . ', '
                . ($support->is_question ? $support->user->getUserLink() : $support->presidentUser->getUserLink())
                . '</div></div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message '
                . ($support->is_question ? 'message-from-me' : 'message-to-me')
                . '">'
                . nl2br($support->text)
                . '</div></div>';
        }

        $result = [
            'offset' => (int)Yii::$app->request->get('offset') + (int)Yii::$app->request->get('limit'),
            'lazy' => $lazy,
            'list' => $list,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
}
