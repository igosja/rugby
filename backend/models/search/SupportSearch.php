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
class SupportSearch extends Support
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['federation_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Support::find()
            ->where(['is_inside' => false])
            ->orderBy(['date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params, '') && $this->validate())) {
            return $dataProvider;
        }

        $query->andWhere([
            'user_id' => $this->user_id,
            'federation_id' => $this->federation_id,
        ]);

        return $dataProvider;
    }
}