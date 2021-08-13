<?php

// TODO refactor

namespace frontend\modules\forum\controllers;

use common\models\db\ForumChapter;
use frontend\controllers\AbstractController;
use Yii;
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

        $this->setSeoTitle($forumChapter->name . ' - ' . Yii::t('frontend', 'modules.forum.controllers.chapter.view.title'));
        return $this->render('view', [
            'forumChapter' => $forumChapter,
        ]);
    }
}
