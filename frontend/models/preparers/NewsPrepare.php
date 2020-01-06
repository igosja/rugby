<?php

namespace frontend\models\preparers;

use frontend\models\queries\NewsQuery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class NewsPrepare
 * @package frontend\models\preparers
 */
class NewsPrepare
{
    /**
     * @return ActiveDataProvider
     */
    public static function getNewsDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNews'],
            ],
            'query' => NewsQuery::getNewsListQuery(),
        ]);
    }
}
