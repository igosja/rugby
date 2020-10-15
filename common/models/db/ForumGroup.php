<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ForumGroup
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property string $description
 * @property int $forum_chapter_id
 * @property string $name
 * @property int $order
 *
 * @property-read Country $country
 * @property-read ForumChapter $forumChapter
 */
class ForumGroup extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%forum_group}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['description', 'forum_chapter_id', 'name', 'order'], 'required'],
            [['country_id', 'order'], 'integer', 'min' => 0, 'max' => 999],
            [['forum_chapter_id'], 'integer', 'min' => 0, 'max' => 9],
            [['description', 'name'], 'trim'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['forum_chapter_id'], 'exist', 'targetRelation' => 'forumChapter'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getForumChapter(): ActiveQuery
    {
        return $this->hasOne(ForumChapter::class, ['id' => 'forum_chapter_id']);
    }
}
