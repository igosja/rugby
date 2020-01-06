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
            ->select([
                'news_comment_id',
            ])
            ->where([
                'news_comment_id' => $id,
                'news_comment_news_id' => $newsId,
            ])
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
            ->with([
                'user' => static function (ActiveQuery $query) {
                    $query->select([
                        'user_id',
                        'user_login',
                        'user_user_role_id',
                    ]);
                },
            ])
            ->select([
                'news_comment_date',
                'news_comment_text',
                'news_comment_user_id',
            ])
            ->where(['news_comment_news_id' => $newsId])
            ->orderBy(['news_comment_id' => SORT_ASC]);
    }
}
