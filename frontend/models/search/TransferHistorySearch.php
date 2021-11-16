<?php

// TODO refactor

namespace frontend\models\search;

use common\models\db\Transfer;
use common\models\db\TransferPosition;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TransferHistorySearch
 * @package frontend\models\search
 */
class TransferHistorySearch extends Transfer
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
            ->joinWith(['player.name', 'player.surname'])
            ->where(['not', ['ready' => null]]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['country_id' => $this->country])
            ->andFilterWhere(['<=', 'transfer.age', $this->ageMax])
            ->andFilterWhere(['>=', 'transfer.age', $this->ageMin])
            ->andFilterWhere(['<=', 'power', $this->powerMax])
            ->andFilterWhere(['>=', 'power', $this->powerMin])
            ->andFilterWhere(['<=', 'price_buyer', $this->priceMax])
            ->andFilterWhere(['>=', 'price_buyer', $this->priceMin])
            ->andFilterWhere(['like', 'name.name', $this->name])
            ->andFilterWhere(['like', 'surname.name', $this->surname]);

        if ($this->position) {
            $query->andWhere([
                'player.id' => TransferPosition::find()
                    ->select(['player_id'])
                    ->where(['position_id' => $this->position])
            ]);
        }

        return $dataProvider;
    }
}