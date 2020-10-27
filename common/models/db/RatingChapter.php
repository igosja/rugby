<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class RatingChapter
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 */
class RatingChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_chapter}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'order'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['order'], 'integer', 'min' => 1, 'max' => 9],
            [['name', 'order'], 'unique'],
        ];
    }
}
