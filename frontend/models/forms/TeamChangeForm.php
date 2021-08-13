<?php

// TODO refactor

namespace frontend\models\forms;

use yii\base\Model;

/**
 * Class TeamChangeForm
 * @package frontend\models\forms
 *
 * @property int $leaveId
 */
class TeamChangeForm extends Model
{
    /**
     * @var int|null $leaveId
     */
    public $leaveId;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['leaveId'], 'integer', 'min' => 1],
        ];
    }
}
