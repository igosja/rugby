<?php

// TODO refactor

namespace backend\models\search;

use common\models\db\Support;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SupportUserSearch
 * @package backend\models
 */
class SupportUserSearch extends Support
{
    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        $query = Support::find()
            ->select([
                'id' => 'MAX(id)',
                'date' => 'MAX(date)',
                'president_user_id',
                'read' => 'IF(MIN(`read`) IS NULL, 0, 1)',
                'user_id',
            ])
            ->where(['is_question' => true, 'is_inside' => false])
            ->groupBy(['user_id'])
            ->orderBy(['read' => SORT_ASC, 'date' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}