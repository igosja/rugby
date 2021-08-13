<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class RatingChapter
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 *
 * @property-read RatingType[] $ratingTypes
 */
class RatingChapter extends AbstractActiveRecord
{
    public const TEAM = 1;
    public const USER = 2;
    public const COUNTRY = 3;

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

    /**
     * @return array
     */
    public static function selectOptions(): array
    {
        /**
         * @var RatingChapter[] $chaptersArray
         */
        $chaptersArray = self::find()
            ->with(['ratingTypes'])
            ->orderBy(['order' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($chaptersArray as $chapter) {
            $result[$chapter->name] = ArrayHelper::map(
                $chapter->ratingTypes,
                'id',
                'name'
            );
        }

        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getRatingTypes(): ActiveQuery
    {
        return $this->hasMany(RatingType::class, ['rating_chapter_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }
}
