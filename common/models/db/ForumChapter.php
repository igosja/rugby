<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ForumChapter
 * @package common\models\db
 *
 * @property int $forum_chapter_id
 * @property string $forum_chapter_name
 * @property int $forum_chapter_order
 */
class ForumChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%forum_chapter}}';
    }
}
