<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\NewsComment;
use yii\db\ActiveQuery;

/**
 * Class NewsCommentQuery
 * @package frontend\models\queries
 */
class NewsCommentQuery
{
    /**
     * @param int $id
     * @param int $newsId
     * @return NewsComment|null
     */
    public static function getNewsCommentById(int $id, int $newsId): ?NewsComment
    {
        /**
         * @var NewsComment $result
         */
        $result = NewsComment::find()
            ->andWhere(['id' => $id, 'news_id' => $newsId])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @param int $newsId
     * @return ActiveQuery
     */
    public static function getNewsCommentListQuery(int $newsId): ActiveQuery
    {
        return NewsComment::find()
            ->with(['user'])
            ->andWhere(['news_id' => $newsId])
            ->orderBy(['id' => SORT_ASC]);
    }
}
