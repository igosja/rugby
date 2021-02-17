<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\FormatHelper;
use common\models\db\Chat;
use common\models\db\User;
use common\models\db\UserBlockType;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class ChatController
 * @package frontend\controllers
 */
class ChatController extends AbstractController
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
        $model = new Chat();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.chat.index.title'));
        return $this->render('index', [
            'model' => $model,
            'user' => $this->user,
            'userBlockChat' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_CHAT)->one() : null,
            'userBlockComment' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one() : null,
        ]);
    }

    /**
     * @return bool[]
     */
    public function actionAdd(): array
    {
        $model = new Chat();
        $model->user_id = $this->user->id;
        if ($model->load(Yii::$app->request->post())) {
            $model->save();

            Chat::deleteAll(['<', 'id', $model->id - 500]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => true];
    }

    /**
     * @param int $lastDate
     * @return array
     */
    public function actionMessage(int $lastDate = 0): array
    {
        $result = [
            'date' => $lastDate,
            'message' => [],
        ];
        $chats = Chat::find()
            ->andWhere(['>', 'date', $lastDate])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($chats as $chat) {
            /**
             * @var Chat $chat
             */
            $result['message'][] = [
                'class' => $chat->user_id === $this->user->id ? 'message-from-me' : 'message-to-me',
                'date' => FormatHelper::asDateTime($chat->date),
                'text' => nl2br($chat->message),
                'userId' => $chat->user_id,
                'userLink' => $chat->user->getUserLink(['target' => '_blank']),
            ];
        }
        if (isset($chat)) {
            $result['date'] = $chat->date;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @return array
     */
    public function actionUser(): array
    {
        $result = [];
        $users = User::find()
            ->select(['login', 'id'])
            ->where('`date_login`>UNIX_TIMESTAMP()-300')
            ->orderBy(['login' => SORT_ASC])
            ->each();
        foreach ($users as $user) {
            /**
             * @var User $user
             */
            $result[] = [
                'user' => $user->getUserLink(['target' => '_blank']),
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
}
