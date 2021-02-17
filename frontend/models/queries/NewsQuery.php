<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\News;
use common\models\db\User;
use yii\db\ActiveQuery;

/**
 * Class NewsQuery
 * @package frontend\models\queries
 */
class NewsQuery
{
    /**
     * @param int $id
     * @return News|null
     */
    public static function getNewsById(int $id): ?News
    {
        /**
         * @var News $result
         */
        $result = News::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public static function getNewsListQuery(): ActiveQuery
    {
        return News::find()
            ->with(['user', 'newsComments'])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @param User $user
     */
    public static function updateUserNewsId(User $user): void
    {
        $lastNewsId = self::getLastNewsId();
        if ($user->news_id < $lastNewsId) {
            User::updateAll(
                ['news_id' => $lastNewsId],
                ['id' => $user->id]
            );
        }
    }

    /**
     * @return int
     */
    public static function getLastNewsId(): int
    {
        return News::find()
            ->select(['id'])
            ->orderBy(['id' => SORT_DESC])
            ->scalar() ?: 0;
    }

    /**
     * @return News
     */
    public static function getLastNews(): ?News
    {
        /**
         * @var News $news
         */
        $news = News::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
        return $news;
    }
}
