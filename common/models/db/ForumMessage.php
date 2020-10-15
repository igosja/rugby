<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ForumMessage
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $date
 * @property int $date_blocked
 * @property int $date_update
 * @property int $forum_theme_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read ForumTheme $forumTheme
 * @property-read User $user
 */
class ForumMessage extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%forum_message}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['forum_theme_id', 'text', 'user_id'], 'required'],
            [['check', 'date_blocked', 'date_update', 'forum_theme_id', 'user_id'], 'integer', 'min' => 0],
            [['text'], 'trim'],
            [['forum_theme_id'], 'exist', 'targetRelation' => 'forumTheme'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getForumTheme(): ActiveQuery
    {
        return $this->hasOne(ForumTheme::class, ['id' => 'forum_theme_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
