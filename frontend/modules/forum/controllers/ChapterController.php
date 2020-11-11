<?php

namespace frontend\modules\forum\controllers;

use common\models\db\ForumChapter;
use frontend\controllers\AbstractController;
use yii\web\NotFoundHttpException;

/**
 * Class ChapterController
 * @package frontend\modules\forum\controllers
 */
class ChapterController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $forumChapter = ForumChapter::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($forumChapter);

        $this->setSeoTitle($forumChapter->name . ' - Форум');
        return $this->render('view', [
            'forumChapter' => $forumChapter,
        ]);
    }
}
