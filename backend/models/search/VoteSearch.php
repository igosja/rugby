<?php

namespace backend\models\search;

use common\models\db\Vote;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class VoteSearch
 * @package backend\models\search
 */
class VoteSearch extends Vote
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}