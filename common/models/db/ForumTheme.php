<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Throwable;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

/**
 * Class ForumTheme
 * @package common\models\db
 *
 * @property int $id
 * @property int $count_view
 * @property int $date
 * @property int $date_update
 * @property int $forum_group_id
 * @property string $name
 * @property int $user_id
 *
 * @property-read ForumGroup $forumGroup
 * @property ForumMessage[] $forumMessages
 * @property-read User $user
 */
class ForumTheme extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%forum_theme}}';
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
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['forum_group_id', 'name', 'user_id'], 'required'],
            [['count_view', 'forum_group_id', 'user_id'], 'integer', 'min' => 0],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['forum_group_id'], 'exist', 'targetRelation' => 'forumGroup'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete(): bool
    {
        foreach ($this->forumMessages as $forumMessage) {
            $forumMessage->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @return ActiveQuery
     */
    public function getForumGroup(): ActiveQuery
    {
        return $this->hasOne(ForumGroup::class, ['id' => 'forum_group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getForumMessages(): ActiveQuery
    {
        return $this
            ->hasMany(ForumMessage::class, ['forum_theme_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
