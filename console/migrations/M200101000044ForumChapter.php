<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000044ForumChapter
 * @package console\migrations
 */
class M200101000044ForumChapter extends Migration
{
    private const TABLE = '{{%forum_chapter}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(255)->notNull()->unique(),
                'order' => $this->integer(1)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'order'],
            [
                [Yii::t('console', 'migrations.forum-chapter.main'), 1],
                [Yii::t('console', 'migrations.forum-chapter.deal'), 2],
                [Yii::t('console', 'migrations.forum-chapter.real'), 3],
                [Yii::t('console', 'migrations.forum-chapter.national'), 4],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
