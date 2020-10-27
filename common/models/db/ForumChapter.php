<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ForumChapter
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 */
class ForumChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%forum_chapter}}';
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
