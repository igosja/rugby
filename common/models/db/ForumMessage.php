<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ForumMessage
 * @package common\models\db
 *
 * @property int $forum_message_id
 * @property int $forum_message_blocked
 * @property int $forum_message_check
 * @property int $forum_message_date
 * @property int $forum_message_date_update
 * @property int $forum_message_forum_theme_id
 * @property string $forum_message_text
 * @property int $forum_message_user_id
 *
 * @property ForumTheme $forumTheme
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
     * @return ActiveQuery
     */
    public function getForumTheme(): ActiveQuery
    {
        return $this->hasOne(ForumTheme::class, ['forum_theme_id' => 'forum_message_forum_theme_id']);
    }
}
