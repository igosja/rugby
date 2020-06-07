<?php

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
     * @var int $ageMax
     */
    public $ageMax;

    /**
     * @var int $ageMin
     */
    public $ageMin;

    /**
     * @var int $country
     */
    public $country;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var int $position
     */
    public $position;

    /**
     * @var int $powerMax
     */
    public $powerMax;

    /**
     * @var int $powerMin
     */
    public $powerMin;

    /**
     * @var int $priceMax
     */
    public $priceMax;

    /**
     * @var int $priceMin
     */
    public $priceMin;

    /**
     * @var string $surname
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
        ];
    }

    public function formName()
    {
        return '';
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
    public function search(array $params = []): ActiveDataProvider
    {
        $query = Player::find()
            ->joinWith(['country', 'name', 'surname', 'team'])
            ->with([
                'playerPositions.position',
                'playerSpecials.special',
                'team.stadium',
                'team.stadium.city',
                'team.stadium.city.country',
            ])
            ->where(['<=', 'player_age', Player::AGE_READY_FOR_PENSION])
            ->andWhere(['!=', 'player_team_id', 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['player_age' => SORT_ASC],
                        'desc' => ['player_age' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['country_name' => SORT_ASC],
                        'desc' => ['country_name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['player_price' => SORT_ASC],
                        'desc' => ['player_price' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['player_position_id' => SORT_ASC],
                        'desc' => ['player_position_id' => SORT_DESC],
                    ],
                    'power' => [
                        'asc' => ['player_power_nominal' => SORT_ASC],
                        'desc' => ['player_power_nominal' => SORT_DESC],
                    ],
                    'surname' => [
                        'asc' => ['surname.surname_name' => SORT_ASC],
                        'desc' => ['surname.surname_name' => SORT_DESC],
                    ],
                    'team' => [
                        'asc' => ['team.team_name' => SORT_ASC],
                        'desc' => ['team.team_name' => SORT_DESC],
                    ],
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['player_country_id' => $this->country])
            ->andFilterWhere(['<=', 'player_age', $this->ageMax])
            ->andFilterWhere(['>=', 'player_age', $this->ageMin])
            ->andFilterWhere(['<=', 'player_power_nominal', $this->powerMax])
            ->andFilterWhere(['>=', 'player_power_nominal', $this->powerMin])
            ->andFilterWhere(['<=', 'player_price', $this->priceMax])
            ->andFilterWhere(['>=', 'player_price', $this->priceMin])
            ->andFilterWhere(['like', 'name_name', $this->name])
            ->andFilterWhere(['like', 'surname_name', $this->surname]);

        if ($this->position) {
            $query->andWhere([
                'player_id' => PlayerPosition::find()
                    ->select(['player_position_player_id'])
                    ->where(['player_position_position_id' => $this->position])
            ]);
        }

        return $dataProvider;
    }
}