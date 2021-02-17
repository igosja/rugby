<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000050Special
 * @package console\migrations
 */
class M200101000050Special extends Migration
{
    private const TABLE = '{{%special}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(2)->notNull()->unique(),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'text'],
            [
                [Yii::t('console', 'migrations.special.name.power'), Yii::t('console', 'migrations.special.text.power')],
                [Yii::t('console', 'migrations.special.name.pass'), Yii::t('console', 'migrations.special.text.pass')],
                [Yii::t('console', 'migrations.special.name.combine'), Yii::t('console', 'migrations.special.text.combine')],
                [Yii::t('console', 'migrations.special.name.scrum'), Yii::t('console', 'migrations.special.text.scrum')],
                [Yii::t('console', 'migrations.special.name.speed'), Yii::t('console', 'migrations.special.text.speed')],
                [Yii::t('console', 'migrations.special.name.tackle'), Yii::t('console', 'migrations.special.text.tackle')],
                [Yii::t('console', 'migrations.special.name.ruck'), Yii::t('console', 'migrations.special.text.ruck')],
                [Yii::t('console', 'migrations.special.name.moul'), Yii::t('console', 'migrations.special.text.moul')],
                [Yii::t('console', 'migrations.special.name.leader'), Yii::t('console', 'migrations.special.text.leader')],
                [Yii::t('console', 'migrations.special.name.athletic'), Yii::t('console', 'migrations.special.text.athletic')],
                [Yii::t('console', 'migrations.special.name.idol'), Yii::t('console', 'migrations.special.text.idol')],
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
