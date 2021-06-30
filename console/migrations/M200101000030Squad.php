<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000030Squad
 * @package console\migrations
 */
class M200101000030Squad extends Migration
{
    private const TABLE = '{{%squad}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'color' => $this->char(6)->unique(),
                'name' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['color', 'name'],
            [
                [null, '------'],
                ['DFF2BF', Yii::t('console', 'migrations.squad.squad', ['squad' => 1])],
                ['C9FFCC', Yii::t('console', 'migrations.squad.squad', ['squad' => 2])],
                ['FEEFB3', Yii::t('console', 'migrations.squad.squad', ['squad' => 3])],
                ['FFBABA', Yii::t('console', 'migrations.squad.squad', ['squad' => 4])],
                ['E0E0E0', Yii::t('console', 'migrations.squad.squad', ['squad' => 5])],
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
