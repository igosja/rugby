<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\FormatHelper;
use common\models\db\Support;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class SupportController
 * @package frontend\controllers
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
     * @return string|Response
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new Support();
        if ($model->addQuestion()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.support.index.success'));
            return $this->refresh();
        }

        $supportArray = Support::find()
            ->where(['user_id' => $this->user->id])
            ->limit(Yii::$app->params['pageSizeMessage'])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $countSupport = Support::find()
            ->where(['user_id' => $this->user->id])
            ->count();

        $lazy = 0;
        if ($countSupport > count($supportArray)) {
            $lazy = 1;
        }

        Support::updateAll(
            ['read' => time()],
            ['read' => null, 'user_id' => $this->user->id, 'is_question' => false]
        );

        $this->setSeoTitle(Yii::t('frontend', 'controllers.support.index.title'));
        return $this->render('index', [
            'lazy' => $lazy,
            'model' => $model,
            'supportArray' => array_reverse($supportArray),
        ]);
    }

    /**
     * @return array
     */
    public function actionLoad(): array
    {
        $supportArray = Support::find()
            ->where(['user_id' => $this->user->id])
            ->offset(Yii::$app->request->get('offset'))
            ->limit(Yii::$app->request->get('limit'))
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $supportArray = array_reverse($supportArray);

        $countSupport = Support::find()
            ->where(['user_id' => $this->user->id])
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
                . ($support->is_question ? $support->user->getUserLink() : $support->adminUser->getUserLink())
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
