<?php

// TODO refactor

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
    public const FEDERATION_AUTO = 12;
    public const FEDERATION_LEAGUE = 13;
    public const FEDERATION_STADIUM = 11;
    public const TEAM_AGE = 2;
    public const TEAM_BASE = 5;
    public const TEAM_FINANCE = 15;
    public const TEAM_PLAYER = 8;
    public const TEAM_POWER = 1;
    public const TEAM_PRICE_BASE = 6;
    public const TEAM_PRICE_STADIUM = 7;
    public const TEAM_PRICE_TOTAL = 9;
    public const TEAM_SALARY = 14;
    public const TEAM_STADIUM = 3;
    public const TEAM_VISITOR = 4;
    public const USER_RATING = 10;

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
