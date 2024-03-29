<?php

// TODO refactor

namespace backend\models\search;

use common\models\db\TeamRequest;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TeamRequestSearch
 * @package backend\models\search
 */
class TeamRequestSearch extends TeamRequest
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