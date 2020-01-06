<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class PollStatus
 * @package common\models\db
 *
 * @property int $poll_status_id
 * @property string $poll_status_name
 */
class PollStatus extends AbstractActiveRecord
{
    const CLOSE = 3;
    const NEW_ONE = 1;
    const OPEN = 2;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%poll_status}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['poll_status_id'], 'integer'],
            [['poll_status_name'], 'required'],
            [['poll_status_name'], 'string', 'max' => 255],
            [['poll_status_name'], 'trim'],
        ];
    }
}
