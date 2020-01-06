<?php

namespace frontend\models\preparers;

use frontend\models\queries\NewsCommentQuery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class NewsCommentPrepare
 * @package frontend\models\preparers
 */
class NewsCommentPrepare
{
    /**
     * @param int $newsId
     * @return ActiveDataProvider
     */
    public static function getNewsCommentDataProvider(int $newsId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNewsComment'],
            ],
            'query' => NewsCommentQuery::getNewsCommentListQuery($newsId),
        ]);
    }
}
