<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Rule
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $order
 * @property string $text
 * @property string $title
 */
class Rule extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rule}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['order', 'title', 'text'], 'required'],
            [['order'], 'integer', 'min' => 1, 'max' => 99],
            [['title', 'text'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['order', 'title'], 'unique'],
        ];
    }
}
