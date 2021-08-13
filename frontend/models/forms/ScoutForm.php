<?php

// TODO refactor

namespace frontend\models\forms;

use yii\base\Model;

/**
 * Class Scout
 * @package frontend\models
 *
 * @property array $style
 */
class ScoutForm extends Model
{
    /**
     * @var array $style
     */
    public $style;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['style'], 'safe'],
            [['style'], 'each', 'rule' => 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function redirectUrl(): array
    {
        return ['scout/study', 'style' => $this->style];
    }
}
