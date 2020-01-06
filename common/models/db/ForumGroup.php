<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ForumGroup
 * @package common\models\db
 *
 * @property int $forum_group_id
 * @property int $forum_group_country_id
 * @property string $forum_group_description
 * @property int $forum_group_forum_chapter_id
 * @property string $forum_group_name
 * @property int $forum_group_order
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
}
