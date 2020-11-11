<?php

namespace frontend\modules\forum\controllers;

use common\models\db\ForumGroup;
use common\models\db\ForumTheme;
use common\models\db\UserBlockType;
use frontend\controllers\AbstractController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class GroupController
 * @package frontend\modules\forum\controllers
 */
class GroupController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $forumGroup = ForumGroup::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($forumGroup);

        $query = ForumTheme::find()
            ->select([
                'forum_theme.*',
                'forum_message_date' => 'MAX(forum_message.date)'
            ])
            ->joinWith(['forumMessages'])
            ->where(['forum_group_id' => $id])
            ->groupBy(['forum_theme.id'])
            ->orderBy(['forum_message_date' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeForum']
            ]
        ]);

        $this->setSeoTitle($forumGroup->name . ' - Форум');
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'forumGroup' => $forumGroup,
            'user' => $this->user,
            'userBlockComment' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one() : null,
            'userBlockForum' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_FORUM)->one() : null,
        ]);
    }
}
