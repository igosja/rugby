<?php

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
