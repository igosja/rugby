<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class VoteStatus
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class VoteStatus extends AbstractActiveRecord
{
    public const CLOSE = 3;
    public const NEW_ONE = 1;
    public const OPEN = 2;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%vote_status}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 25],
            [['name'], 'unique'],
        ];
    }
}
