<?php

// TODO refactor

namespace frontend\models\search;

use common\models\db\Loan;
use common\models\db\LoanPosition;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class LoanHistorySearch
 * @package frontend\models\search
 */
class LoanHistorySearch extends Loan
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
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Loan::find()
            ->joinWith(['player.name', 'player.surname'])
            ->where(['not', ['ready' => null]])
            ->orderBy(['ready' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['country_id' => $this->country])
            ->andFilterWhere(['<=', 'loan.age', $this->ageMax])
            ->andFilterWhere(['>=', 'loan.age', $this->ageMin])
            ->andFilterWhere(['<=', 'power', $this->powerMax])
            ->andFilterWhere(['>=', 'power', $this->powerMin])
            ->andFilterWhere(['<=', 'price_buyer', $this->priceMax])
            ->andFilterWhere(['>=', 'price_buyer', $this->priceMin])
            ->andFilterWhere(['like', 'name.name', $this->name])
            ->andFilterWhere(['like', 'surname.name', $this->surname]);

        if ($this->position) {
            $query->andWhere([
                'id' => LoanPosition::find()
                    ->select(['loan_id'])
                    ->where(['position_id' => $this->position])
            ]);
        }

        return $dataProvider;
    }
}