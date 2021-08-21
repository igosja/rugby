<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\TranslateKey;
use common\models\db\TranslateOption;
use common\models\db\TranslateVote;
use Yii;
use yii\filters\AccessControl;

/**
 * Class TranslateController
 * @package frontend\controllers
 */
class TranslateController extends AbstractController
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
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $translateKey = TranslateKey::find()
            ->andWhere([
                'not',
                [
                    'id' => TranslateOption::find()
                        ->select(['translate_key_id'])
                        ->andWhere([
                            'id' => TranslateVote::find()
                                ->select(['id'])
                                ->andWhere(['user_id' => $this->user->id])
                        ])
                ]
            ])
            ->orderBy('RAND()')
            ->limit(1)
            ->one();
        if (!$translateKey) {
            $translateKey = TranslateKey::find()
                ->orderBy('RAND()')
                ->limit(1)
                ->one();
        }

        $translateVote = TranslateVote::find()
            ->andWhere([
                'translate_option_id' => TranslateOption::find()
                    ->select(['id'])
                    ->andWhere(['translate_key_id' => $translateKey->id]),
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$translateVote) {
            $translateVote = new TranslateVote();
            $translateVote->user_id = $this->user->id;
        }

        $translateOption = new TranslateOption();
        $translateOption->translate_key_id = $translateKey->id;
        $translateOption->user_id = $this->user->id;
        if ($translateOption->load(Yii::$app->request->post()) && $translateOption->save()) {
            $translateVote->translate_option_id = $translateOption->id;
            $translateVote->save();

            $this->setSuccessFlash(Yii::t('frontend', 'controllers.translate.index.success.option'));
            return $this->refresh();
        }

        if ($translateVote->load(Yii::$app->request->post()) && $translateVote->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.translate.index.success.vote'));
            return $this->refresh();
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.translate.index.title'));
        return $this->render('index', [
            'translateKey' => $translateKey,
            'translateOption' => $translateOption,
            'translateVote' => $translateVote,
        ]);
    }
}
