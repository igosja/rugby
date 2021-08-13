<?php

// TODO refactor

namespace frontend\models\search;

use common\models\db\Player;
use common\models\db\PlayerPosition;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class PlayerSearch
 * @package frontend\models\search
 */
class PlayerSearch extends Player
{
    /**
     * @var int|null $ageMax
     */
    public $ageMax;

    /**
     * @var int|null $ageMin
     */
    public $ageMin;

    /**
     * @var int|null $country
     */
    public $country;

    /**
     * @var string|null $name
     */
    public $name;

    /**
     * @var int|null $position
     */
    public $position;

    /**
     * @var int|null $powerMax
     */
    public $powerMax;

    /**
     * @var int|null $powerMin
     */
    public $powerMin;

    /**
     * @var int|null $priceMax
     */
    public $priceMax;

    /**
     * @var int|null $priceMin
     */
    public $priceMin;

    /**
     * @var string|null $surname
     */
    public $surname;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                ['ageMax', 'ageMin', 'country', 'position', 'powerMax', 'powerMin', 'priceMax', 'priceMin'],
                'integer',
                'min' => 0
            ],
            [['name', 'surname'], 'trim'],
            [['name', 'surname'], 'string'],
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
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
     * @return ActiveDataProvider
     */
    public function search(array $params = []): ActiveDataProvider
    {
        $query = Player::find()
            ->joinWith(['country', 'name', 'surname', 'team'])
            ->where(['<=', 'age', Player::AGE_READY_FOR_PENSION])
            ->andWhere(['!=', 'team_id', 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['age' => SORT_ASC],
                        'desc' => ['age' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['country.name' => SORT_ASC],
                        'desc' => ['country.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['price' => SORT_ASC],
                        'desc' => ['price' => SORT_DESC],
                    ],
                    'power' => [
                        'asc' => ['power_nominal' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC],
                    ],
                    'surname' => [
                        'asc' => ['surname.name' => SORT_ASC],
                        'desc' => ['surname.name' => SORT_DESC],
                    ],
                    'team' => [
                        'asc' => ['team.name' => SORT_ASC],
                        'desc' => ['team.name' => SORT_DESC],
                    ],
                ]
            ]
        ]);

        $params = $this->clearParams($params);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['country_id' => $this->country])
            ->andFilterWhere(['<=', 'age', $this->ageMax])
            ->andFilterWhere(['>=', 'age', $this->ageMin])
            ->andFilterWhere(['<=', 'power_nominal', $this->powerMax])
            ->andFilterWhere(['>=', 'power_nominal', $this->powerMin])
            ->andFilterWhere(['<=', 'price', $this->priceMax])
            ->andFilterWhere(['>=', 'price', $this->priceMin])
            ->andFilterWhere(['like', 'name.name', $this->name])
            ->andFilterWhere(['like', 'surname.name', $this->surname]);

        if ($this->position) {
            $query->andWhere([
                'player.id' => PlayerPosition::find()
                    ->select(['player_id'])
                    ->where(['position_id' => $this->position])
            ]);
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return array
     */
    private function clearParams(array $params): array
    {
        foreach ($params as $key => $value) {
            if ('' === trim($value)) {
                $params[$key] = null;
            }
        }
        return $params;
    }
}