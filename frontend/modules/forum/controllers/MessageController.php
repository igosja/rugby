<?php

namespace frontend\modules\forum\controllers;

use common\models\db\Complaint;
use common\models\db\ForumMessage;
use common\models\db\ForumTheme;
use common\models\db\UserRole;
use Exception;
use frontend\controllers\AbstractController;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class MessageController
 * @package frontend\modules\forum\controllers
 */
class MessageController extends AbstractController
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
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionComplaint(int $id): Response
    {
        $forumMessage = ForumMessage::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($forumMessage);

        $model = new Complaint();
        $model->forum_message_id = $id;
        $model->text = 'fuck';
        $model->user_id = $this->user->id;
        $model->save();

        $this->setSuccessFlash('Жалоба успешно сохранена');
        return $this->redirect(
            Yii::$app->request->referrer ?: ['theme/view', 'id' => $forumMessage->forum_theme_id]
        );
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionBlock($id): Response
    {
        if (UserRole::USER === $this->user->user_role_id) {
            $this->forbiddenRole();
        }

        $model = ForumMessage::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->date_blocked) {
            $model->date_blocked = null;
        } else {
            $model->date_blocked = time();
        }
        $model->save(true, ['date_blocked']);

        $this->setSuccessFlash();
        return $this->redirect(
            Yii::$app->request->referrer ?: ['forum/theme', 'id' => $model->forum_theme_id]
        );
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): Response
    {
        $userId = null;
        if (UserRole::USER === $this->user->user_role_id) {
            $userId = $this->user->id;
        }

        $model = ForumMessage::find()
            ->where(['id' => $id])
            ->andFilterWhere(['user_id' => $userId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $themeId = $model->forum_theme_id;
        $model->delete();

        $this->setSuccessFlash('Сообшение успешно удалено');
        return $this->redirect(Yii::$app->request->referrer ?: ['forum/theme', 'id' => $themeId]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionEdit($id)
    {
        $userId = null;
        if (UserRole::USER === $this->user->user_role_id) {
            $userId = $this->user->id;
        }

        $model = ForumMessage::find()
            ->where(['id' => $id])
            ->andFilterWhere(['user_id' => $userId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->date_blocked && UserRole::USER === $this->user->user_role_id) {
            $this->setErrorFlash('Сообщение заблокировано и не может быть отредактировано');
            return $this->redirect(
                Yii::$app->request->referrer ?: ['theme/view', 'id' => $model->forum_theme_id]
            );
        }

        $model->date_update = time();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Сообщение успешно отредактировано');
            return $this->redirect(['theme/view', 'id' => $model->forum_theme_id]);
        }

        $this->setSeoTitle('Редактирование сообщения');

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionMove($id)
    {
        if (UserRole::USER === $this->user->referrer_user_id) {
            $this->forbiddenRole();
        }

        $model = ForumMessage::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Сообщение успешно отредактировано');
            return $this->redirect(['theme/view', 'id' => $model->forum_theme_id]);
        }

        $forumThemeOptions = [];
        $forumThemeArray = ForumTheme::find()
            ->joinWith(['forumGroup.forumChapter'])
            ->orderBy(['forum_chapter.name' => SORT_ASC, 'forum_group.name' => SORT_ASC])
            ->all();
        foreach ($forumThemeArray as $forumTheme) {
            /**
             * @var ForumTheme $forumTheme
             */
            $forumThemeOptions[$forumTheme->id] = $forumTheme->forumGroup->forumChapter->name
                . ' --- '
                . $forumTheme->forumGroup->name
                . ' --- '
                . $forumTheme->name;
        }

        $this->setSeoTitle('Перемещение сообщения');
        return $this->render('move', [
            'forumThemeArray' => $forumThemeOptions,
            'model' => $model,
        ]);
    }
}
