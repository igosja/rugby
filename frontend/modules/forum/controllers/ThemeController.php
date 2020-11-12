<?php

namespace frontend\modules\forum\controllers;

use common\models\db\ForumGroup;
use common\models\db\ForumMessage;
use common\models\db\ForumTheme;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use frontend\controllers\AbstractController;
use frontend\models\forms\ForumThemeForm;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ThemeController
 * @package frontend\modules\forum\controllers
 */
class ThemeController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $forumTheme = ForumTheme::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($forumTheme);

        $model = new ForumMessage();
        $model->user_id = $this->user->id;
        $model->forum_theme_id = $id;
        if ($model->addMessage()) {
            $this->setSuccessFlash('Сообщение успешно сохранено');
            return $this->refresh();
        }

        $query = ForumMessage::find()
            ->andWhere(['forum_theme_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeForum']
            ]
        ]);

        if (!Yii::$app->request->get('page')) {
            $lastPage = ceil($query->count() / Yii::$app->params['pageSizeForum']);
            Yii::$app->request->setQueryParams(
                ArrayHelper::merge(Yii::$app->request->queryParams, ['page' => $lastPage])
            );
        }

        $forumTheme->count_view++;
        $forumTheme->save();

        $this->setSeoTitle($forumTheme->name . ' - Форум');

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'forumTheme' => $forumTheme,
            'model' => $model,
            'user' => $this->user,
            'userBlockComment' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one() : null,
            'userBlockForum' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_FORUM)->one() : null,
        ]);
    }

    /**
     * @param int $groupId
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionCreate(int $groupId)
    {
        if (!$this->user->date_confirm) {
            return $this->redirect(['group/view', 'id' => $groupId]);
        }
        /**
         * @var UserBlock $userBlockForum
         */
        $userBlockForum = $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one();
        /**
         * @var UserBlock $userBlockComment
         */
        $userBlockComment = $this->user->getUserBlock(UserBlockType::TYPE_FORUM)->one();
        if (($userBlockForum && $userBlockForum->date > time()) || ($userBlockComment && $userBlockComment->date > time())) {
            return $this->redirect(['group/view', 'id' => $groupId]);
        }

        $forumGroup = ForumGroup::find()
            ->where(['id' => $groupId])
            ->limit(1)
            ->one();
        $this->notFound($forumGroup);

        $model = new ForumThemeForm();
        if ($model->create($groupId)) {
            $this->setSuccessFlash('Тема успешно создана');
            return $this->redirect(['view', 'id' => $model->getThemeId()]);
        }

        $this->setSeoTitle('Создание темы - ' . $forumGroup->name . ' - Форум');

        return $this->render('create', [
            'forumGroup' => $forumGroup,
            'model' => $model,
        ]);
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
        $model = ForumTheme::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $groupId = $model->forum_group_id;

        $model->delete();

        $this->setSuccessFlash('Тема успешно удалена');
        return $this->redirect(Yii::$app->request->referrer ?: ['group/view', 'id' => $groupId]);
    }
}
