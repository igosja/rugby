<?php

// TODO refactor

namespace frontend\models\search;

use common\models\db\PlayerPosition;
use common\models\db\Transfer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TransferSearch
 * @package frontend\models\search
 */
class TransferSearch extends Transfer
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
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = self::find()
            ->joinWith(['player.country', 'player.name', 'player.surname'])
            ->where(['ready' => null]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['player.age' => SORT_ASC],
                        'desc' => ['player.age' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['country.name' => SORT_ASC],
                        'desc' => ['country.name' => SORT_DESC],
                    ],
                    'power' => [
                        'asc' => ['power_nominal' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['price_seller' => SORT_ASC],
                        'desc' => ['price_seller' => SORT_DESC],
                    ],
                    'id',
                ],
                'defaultOrder' => ['id' => SORT_ASC],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['country_id' => $this->country])
            ->andFilterWhere(['<=', 'player.age', $this->ageMax])
            ->andFilterWhere(['>=', 'player.age', $this->ageMin])
            ->andFilterWhere(['<=', 'power_nominal', $this->powerMax])
            ->andFilterWhere(['>=', 'power_nominal', $this->powerMin])
            ->andFilterWhere(['<=', 'price_seller', $this->priceMax])
            ->andFilterWhere(['>=', 'price_seller', $this->priceMin])
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
}