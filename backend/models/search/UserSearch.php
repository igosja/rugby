<?php

namespace backend\models\search;

use common\models\db\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 * @package backend\models
 */
class UserSearch extends User
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['email', 'login'], 'string'],
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
                    'defaultOrder' => ['id' => SORT_DESC],
                ],
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'login', $this->login]);

        return $dataProvider;
    }
}