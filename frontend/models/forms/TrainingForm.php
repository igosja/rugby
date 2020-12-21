<?php

// TODO refactor

namespace frontend\models\forms;

use yii\base\Model;

/**
 * Class Training
 * @package frontend\models
 *
 * @property array $position
 * @property array $power
 * @property array $special
 */
class TrainingForm extends Model
{
    /**
     * @var array $position
     */
    public $position;

    /**
     * @var array $power
     */
    public $power;

    /**
     * @var array $special
     */
    public $special;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['power', 'position', 'special'], 'safe'],
            [['power', 'position', 'special'], 'each', 'rule' => 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function redirectUrl(): array
    {
        $positionArray = [];
        foreach ($this->position as $player => $position) {
            if ($position) {
                $positionArray[$player] = $position;
            }
        }
        $specialArray = [];
        foreach ($this->special as $player => $special) {
            if ($special) {
                $specialArray[$player] = $special;
            }
        }
        $this->position = $positionArray;
        $this->special = $specialArray;

        return ['training/train', 'position' => $this->position, 'power' => $this->power, 'special' => $this->special];
    }
}
