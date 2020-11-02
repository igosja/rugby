<?php

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
            ->select(['id'])
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
            ->with(
                [
                    'user' => static function (ActiveQuery $query) {
                        $query->select(['id', 'login', 'user_role_id']);
                    },
                ]
            )
            ->select(['date', 'text', 'user_id'])
            ->andWhere(['news_id' => $newsId])
            ->orderBy(['id' => SORT_ASC]);
    }
}
