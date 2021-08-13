<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000045ForumGroup
 * @package console\migrations
 */
class M200101000045ForumGroup extends Migration
{
    private const TABLE = '{{%forum_group}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'federation_id' => $this->integer(3),
                'description' => $this->text()->notNull(),
                'forum_chapter_id' => $this->integer(1)->notNull(),
                'name' => $this->string(255)->notNull()->unique(),
                'order' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('forum_group_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey(
            'forum_group_forum_chapter_id',
            self::TABLE,
            'forum_chapter_id',
            '{{%forum_chapter}}',
            'id'
        );

        $this->createIndex('forum_group_order', self::TABLE, ['forum_chapter_id', 'order'], true);

        $this->batchInsert(
            self::TABLE,
            [
                'federation_id',
                'name',
                'description',
                'forum_chapter_id',
                'order'
            ],
            [
                [
                    null,
                    Yii::t('console', 'migrations.forum-group.name.league'),
                    Yii::t('console', 'migrations.forum-group.description.league'),
                    1,
                    1
                ],
                [
                    null,
                    Yii::t('console', 'migrations.forum-group.name.help'),
                    Yii::t('console', 'migrations.forum-group.description.help'),
                    1,
                    2
                ],
                [
                    null,
                    Yii::t('console', 'migrations.forum-group.name.new'),
                    Yii::t('console', 'migrations.forum-group.description.new'),
                    1,
                    3
                ],
                [
                    null,
                    Yii::t('console', 'migrations.forum-group.name.team'),
                    Yii::t('console', 'migrations.forum-group.description.team'),
                    1,
                    4
                ],
                [
                    null,
                    Yii::t('console', 'migrations.forum-group.name.idea'),
                    Yii::t('console', 'migrations.forum-group.description.idea'),
                    1,
                    5
                ],
                [null, Yii::t('console', 'migrations.forum-group.name.rule'), Yii::t('console', 'migrations.forum-group.description.rule'), 1, 6],
                [null, Yii::t('console', 'migrations.forum-group.name.bug'), Yii::t('console', 'migrations.forum-group.description.bug'), 1, 7],
                [null, Yii::t('console', 'migrations.forum-group.name.transfer'), Yii::t('console', 'migrations.forum-group.description.transfer'), 2, 1],
                [null, Yii::t('console', 'migrations.forum-group.name.friendly'), Yii::t('console', 'migrations.forum-group.description.friendly'), 2, 2],
                [null, Yii::t('console', 'migrations.forum-group.name.loan'), Yii::t('console', 'migrations.forum-group.description.loan'), 2, 3],
                [null, Yii::t('console', 'migrations.forum-group.name.visit'), Yii::t('console', 'migrations.forum-group.description.visit'), 3, 1],
                [null, Yii::t('console', 'migrations.forum-group.name.real'), Yii::t('console', 'migrations.forum-group.description.real'), 3, 2],
                [null, Yii::t('console', 'migrations.forum-group.name.off'), Yii::t('console', 'migrations.forum-group.description.off'), 3, 3],
                [7, 'Argentina', Yii::t('console', 'migrations.forum-group.description.national'), 4, 1],
                [9, 'Australia', Yii::t('console', 'migrations.forum-group.description.national'), 4, 2],
                [54, 'England', Yii::t('console', 'migrations.forum-group.description.national'), 4, 3],
                [61, 'France', Yii::t('console', 'migrations.forum-group.description.national'), 4, 4],
                [81, 'Ireland', Yii::t('console', 'migrations.forum-group.description.national'), 4, 5],
                [83, 'Italy', Yii::t('console', 'migrations.forum-group.description.national'), 4, 6],
                [124, 'New Zealand', Yii::t('console', 'migrations.forum-group.description.national'), 4, 7],
                [154, 'Scotland', Yii::t('console', 'migrations.forum-group.description.national'), 4, 8],
                [164, 'South Africa', Yii::t('console', 'migrations.forum-group.description.national'), 4, 9],
                [194, 'Wales', Yii::t('console', 'migrations.forum-group.description.national'), 4, 10],
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
