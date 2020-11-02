<?php

namespace backend\models\search;

use common\models\db\Team;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TeamSearch
 * @package backend\models\search
 */
class TeamSearch extends Team
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string'],
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
        $query = self::find()
            ->andWhere(['!=', 'id', 0]);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['id' => SORT_ASC],
                ],
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}