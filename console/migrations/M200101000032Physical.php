<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000032Physical
 * @package console\migrations
 */
class M200101000032Physical extends Migration
{
    private const TABLE = '{{%physical}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(20)->notNull()->unique(),
                'opposite_physical_id' => $this->integer(2)->notNull()->unique(),
                'value' => $this->integer(3)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'opposite_physical_id', 'value'],
            [
                [Yii::t('console', 'migrations.physical.down', ['percent' => 125]), 1, 125],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 120]), 20, 120],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 115]), 19, 115],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 110]), 18, 110],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 105]), 17, 105],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 100]), 16, 100],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 95]), 15, 95],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 90]), 14, 90],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 85]), 13, 85],
                [Yii::t('console', 'migrations.physical.down', ['percent' => 80]), 12, 80],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 75]), 11, 75],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 80]), 10, 80],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 85]), 9, 85],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 90]), 8, 90],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 95]), 7, 95],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 100]), 6, 100],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 105]), 5, 105],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 110]), 4, 110],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 115]), 3, 115],
                [Yii::t('console', 'migrations.physical.up', ['percent' => 120]), 2, 120],
                ['-', 21, 0],
            ]
        );

        $this->addForeignKey('physical_opposite_physical_id', self::TABLE, 'opposite_physical_id', self::TABLE, 'id');

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
