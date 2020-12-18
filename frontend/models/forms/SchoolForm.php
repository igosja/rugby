<?php

// TODO refactor

namespace frontend\models\forms;

use yii\base\Model;

/**
 * Class School
 * @package frontend\models
 *
 * @property array $position_id
 * @property array $special_id
 * @property array $style_id
 */
class SchoolForm extends Model
{
    /**
     * @var array $position_id
     */
    public $position_id;

    /**
     * @var array $special_id
     */
    public $special_id;

    /**
     * @var array $style_id
     */
    public $style_id;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['position_id', 'special_id', 'style_id'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function redirectUrl(): array
    {
        return [
            'school/start',
            'position_id' => $this->position_id,
            'special_id' => $this->special_id,
            'style_id' => $this->style_id,
        ];
    }
}
