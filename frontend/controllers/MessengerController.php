<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\FormatHelper;
use common\models\db\Blacklist;
use common\models\db\Message;
use common\models\db\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class MessengerController
 * @package frontend\controllers
 */
class MessengerController extends AbstractController
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
     * @return string
     */
    public function actionIndex(): string
    {
        $query = Message::find()
            ->select([
                'id' => 'MAX(`id`)',
                'user_id' => 'IF(`to_user_id`=' . $this->user->id . ', `from_user_id`, `to_user_id`)',
                'from_user_id',
                'to_user_id',
                'read' => 'MIN(IF(`to_user_id`=' . $this->user->id . ', IF(`read` IS NULL, 0, 1), 1))',
            ])
            ->where([
                'or',
                ['from_user_id' => $this->user->id],
                ['to_user_id' => $this->user->id]
            ])
            ->groupBy('user_id')
            ->orderBy(['read' => SORT_ASC, 'id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.messenger.index.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $user = User::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($user);

        $model = new Message();
        if ($model->addMessage($id)) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.messenger.view.success'));
            return $this->refresh();
        }

        $messageArray = Message::find()
            ->where([
                'or',
                ['from_user_id' => $id, 'to_user_id' => $this->user->id],
                ['from_user_id' => $this->user->id, 'to_user_id' => $id]
            ])
            ->limit(Yii::$app->params['pageSizeMessage'])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $countMessage = Message::find()
            ->where([
                'or',
                ['from_user_id' => $id, 'to_user_id' => $this->user->id],
                ['from_user_id' => $this->user->id, 'to_user_id' => $id]
            ])
            ->count();

        $lazy = 0;
        if ($countMessage > count($messageArray)) {
            $lazy = 1;
        }

        Message::updateAll(
            ['read' => time()],
            ['read' => null, 'to_user_id' => $this->user->id, 'from_user_id' => $id]
        );

        $inBlacklistOwner = Blacklist::find()
            ->where(['owner_user_id' => $this->user->id, 'blocked_user_id' => $id])
            ->count();

        $inBlacklistInterlocutor = Blacklist::find()
            ->where(['owner_user_id' => $id, 'blocked_user_id' => $this->user->id])
            ->count();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.messenger.view.title'));
        return $this->render('view', [
            'inBlacklistInterlocutor' => $inBlacklistInterlocutor,
            'inBlacklistOwner' => $inBlacklistOwner,
            'lazy' => $lazy,
            'model' => $model,
            'messageArray' => array_reverse($messageArray),
        ]);
    }

    /**
     * @param int $id
     * @return array
     */
    public function actionLoad(int $id): array
    {
        $messageArray = Message::find()
            ->where([
                'or',
                ['from_user_id' => $id, 'to_user_id' => $this->user->id],
                ['from_user_id' => $this->user->id, 'to_user_id' => $id]
            ])
            ->offset(Yii::$app->request->get('offset'))
            ->limit(Yii::$app->request->get('limit'))
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $messageArray = array_reverse($messageArray);

        $countMessage = Message::find()
            ->where([
                'or',
                ['from_user_id' => $id, 'to_user_id' => $this->user->id],
                ['from_user_id' => $this->user->id, 'to_user_id' => $id]
            ])
            ->count();

        if ($countMessage > count($messageArray) + Yii::$app->request->get('offset')) {
            $lazy = 1;
        } else {
            $lazy = 0;
        }

        $list = '';

        foreach ($messageArray as $message) {
            /**
             * @var Message $message
             */
            $list .= '<div class="row margin-top"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-size-3">'
                . FormatHelper::asDateTime($message->date)
                . ', '
                . $message->fromUser->getUserLink()
                . '</div></div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message '
                . ($this->user->id === $message->from_user_id ? 'message-from-me' : 'message-to-me')
                . '">'
                . nl2br($message->text)
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
