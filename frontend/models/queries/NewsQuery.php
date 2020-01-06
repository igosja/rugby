<?php

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
     * @param int $countryId
     * @return News|null
     */
    public static function getNewsById(int $id, int $countryId = 0): ?News
    {
        /**
         * @var News $result
         */
        $result = News::find()
            ->with([
                'user' => static function (ActiveQuery $query) {
                    $query->select([
                        'user_id',
                        'user_login',
                    ]);
                },
            ])
            ->select([
                'news_date',
                'news_title',
                'news_text',
                'news_user_id',
            ])
            ->where(['news_id' => $id, 'news_country_id' => $countryId])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @param int $countryId
     * @return ActiveQuery
     */
    public static function getNewsListQuery(int $countryId = 0): ActiveQuery
    {
        return News::find()
            ->with([
                'newsComments' => static function (ActiveQuery $query) {
                    $query->select([
                        'news_comment_news_id',
                    ]);
                },
                'user' => static function (ActiveQuery $query) {
                    $query->select([
                        'user_id',
                        'user_login',
                    ]);
                },
            ])
            ->select([
                'news_date',
                'news_id',
                'news_title',
                'news_text',
                'news_user_id',
            ])
            ->where(['news_country_id' => $countryId])
            ->orderBy(['news_id' => SORT_DESC]);
    }

    /**
     * @param User $user
     */
    public static function updateUserNewsId(User $user): void
    {
        $lastNewsId = self::getLastNewsId();
        if ($user->user_news_id < $lastNewsId) {
            User::updateAll(
                ['user_news_id' => $lastNewsId],
                ['user_id' => $user->user_id]
            );
        }
    }

    /**
     * @param int $countryId
     * @return int
     */
    public static function getLastNewsId(int $countryId = 0): int
    {
        return News::find()
            ->select(['news_id'])
            ->where(['news_country_id' => $countryId])
            ->orderBy(['news_id' => SORT_DESC])
            ->scalar() ?: 0;
    }
}
