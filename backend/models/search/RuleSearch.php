<?php

namespace backend\models\search;

use common\models\db\Rule;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class RuleSearch
 * @package backend\models
 */
class RuleSearch extends Rule
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['title'], 'string'],
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
                    'defaultOrder' => ['order' => SORT_ASC],
                ],
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}