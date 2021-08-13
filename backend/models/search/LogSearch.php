<?php

// TODO refactor

namespace backend\models\search;

use common\models\db\Log;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class LogSearch
 * @package backend\models\search
 */
class LogSearch extends Log
{
    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC],
                ],
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
