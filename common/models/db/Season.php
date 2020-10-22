<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Season
 * @package common\models\db
 *
 * @property int $id
 * @property bool $is_future
 */
class Season extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%season}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['is_future'], 'boolean'],
        ];
    }
}
