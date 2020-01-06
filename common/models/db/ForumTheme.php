<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ForumTheme
 * @package common\models\db
 *
 * @property int $forum_theme_id
 * @property int $forum_theme_count_view
 * @property int $forum_theme_date
 * @property int $forum_theme_date_update
 * @property int $forum_theme_forum_group_id
 * @property string $forum_theme_name
 * @property int $forum_theme_user_id
 *
 * @property ForumGroup $forumGroup
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
     * @return ActiveQuery
     */
    public function getForumGroup(): ActiveQuery
    {
        return $this->hasOne(ForumGroup::class, ['forum_group_id' => 'forum_theme_forum_group_id']);
    }
}
