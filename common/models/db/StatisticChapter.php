<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class StatisticChapter
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 */
class StatisticChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_chapter}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'order'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 10],
            [['order'], 'integer', 'min' => 1, 'max' => 9],
            [['name', 'order'], 'unique'],
        ];
    }
}
