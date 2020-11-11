<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ForumChapter
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 *
 * @property ForumGroup[] $forumGroups
 */
class ForumChapter extends AbstractActiveRecord
{
    public const NATIONAL = 4;

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

    /**
     * @return ActiveQuery
     */
    public function getForumGroups(): ActiveQuery
    {
        return $this->hasMany(ForumGroup::class, ['forum_chapter_id' => 'id']);
    }
}
