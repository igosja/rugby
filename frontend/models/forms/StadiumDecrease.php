<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\Stadium;
use yii\base\Model;

/**
 * Class ChangePassword
 * @package frontend\models
 *
 * @property int $capacity
 * @property Stadium $stadium
 */
class StadiumDecrease extends Model
{
    public const ONE_SIT_PRICE = 150;

    public $capacity;
    private $stadium;

    /**
     * StadiumIncrease constructor.
     * @param Stadium $stadium
     * @param array $config
     */
    public function __construct(Stadium $stadium, array $config = [])
    {
        $this->stadium = $stadium;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                ['capacity'],
                'integer',
                'min' => 1,
                'max' => $this->stadium->capacity - 1,
            ],
            [['capacity'], 'required'],
        ];
    }
}
