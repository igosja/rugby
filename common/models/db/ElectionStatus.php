<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionStatus
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class ElectionStatus extends AbstractActiveRecord
{
    public const CANDIDATES = 1;
    public const CLOSE = 3;
    public const OPEN = 2;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_status}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }
}
