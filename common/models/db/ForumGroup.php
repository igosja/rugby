<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ForumGroup
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property string $description
 * @property int $forum_chapter_id
 * @property string $name
 * @property int $order
 *
 * @property-read Federation $federation
 * @property-read ForumChapter $forumChapter
 * @property-read ForumTheme[] $forumThemes
 * @property-read ForumMessage $lastForumMessage
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
            [['federation_id', 'order'], 'integer', 'min' => 0, 'max' => 999],
            [['forum_chapter_id'], 'integer', 'min' => 0, 'max' => 9],
            [['description', 'name'], 'trim'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['forum_chapter_id'], 'exist', 'targetRelation' => 'forumChapter'],
        ];
    }

    /**
     * @return int
     */
    public function countMessage(): int
    {
        $result = 0;
        foreach ($this->forumThemes as $forumTheme) {
            $result += count($forumTheme->forumMessages);
        }
        return $result;
    }

    /**
     * @return int
     */
    public function countTheme(): int
    {
        return count($this->forumThemes);
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getForumChapter(): ActiveQuery
    {
        return $this->hasOne(ForumChapter::class, ['id' => 'forum_chapter_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getForumThemes(): ActiveQuery
    {
        return $this->hasMany(ForumTheme::class, ['forum_group_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLastForumMessage(): ActiveQuery
    {
        return $this
            ->hasOne(ForumMessage::class, ['forum_theme_id' => 'id'])
            ->via('forumThemes')
            ->orderBy(['id' => SORT_DESC]);
    }
}
