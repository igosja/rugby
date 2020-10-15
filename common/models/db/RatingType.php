<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class RatingType
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 * @property int $rating_chapter_id
 *
 * @property-read RatingChapter $ratingChapter
 */
class RatingType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_type}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'order', 'rating_chapter_id'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['rating_chapter_id'], 'integer', 'min' => 1, 'max' => 9],
            [['order'], 'integer', 'min' => 1, 'max' => 99],
            [['rating_chapter_id'], 'exist', 'targetRelation' => 'ratingChapter'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRatingChapter(): ActiveQuery
    {
        return $this->hasOne(RatingChapter::class, ['id' => 'rating_chapter_id']);
    }
}
