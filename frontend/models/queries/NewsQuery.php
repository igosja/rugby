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
     * @param int|null $federationId
     * @return News|null
     */
    public static function getNewsById(int $id, int $federationId = null): ?News
    {
        /**
         * @var News $result
         */
        $result = News::find()
            ->andWhere(['id' => $id])
            ->andFilterWhere(['federation_id' => $federationId])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @param int|null $federationId
     * @return ActiveQuery
     */
    public static function getNewsListQuery(int $federationId = null): ActiveQuery
    {
        return News::find()
            ->andFilterWhere(['federation_id' => $federationId])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @param User $user
     */
    public static function updateUserNewsId(User $user): void
    {
        $lastNewsId = self::getLastNewsId();
        if ($user->id < $lastNewsId) {
            User::updateAll(
                ['news_id' => $lastNewsId],
                ['id' => $user->id]
            );
        }
    }

    /**
     * @param int|null $federationId
     * @return int
     */
    public static function getLastNewsId(int $federationId = null): int
    {
        return News::find()
            ->select(['id'])
            ->andFilterWhere(['federation_id' => $federationId])
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
            ->andWhere(['federation_id' => null])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
        return $news;
    }
}
