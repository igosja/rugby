<?php

namespace frontend\models\queries;

use common\models\db\ForumMessage;

/**
 * Class ForumMessageQuery
 * @package frontend\models\queries
 */
class ForumMessageQuery
{
    /**
     * @return ForumMessage[]
     */
    public static function getLastForumGroupsByMessageDate(): array
    {
        /**
         * @var ForumMessage[] $forumMessages
         */
        $forumMessages = ForumMessage::find()
            ->select(
                [
                    'forum_message.*',
                    'id' => 'MAX(forum_message.id)',
                    'date' => 'MAX(forum_message.date)',
                ]
            )
            ->joinWith(['forumTheme.forumGroup'])
            ->andWhere(
                [
                    'forum_group.country_id' => 0
                ]
            )
            ->groupBy(['forum_theme_id'])
            ->orderBy(['forum_message.id' => SORT_DESC])
            ->limit(10)
            ->all();

        return $forumMessages;
    }
}
