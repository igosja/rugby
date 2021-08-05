<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000049Tactic
 * @package console\migrations
 */
class M200101000049Tactic extends Migration
{
    private const TABLE = '{{%tactic}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(30)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                [Yii::t('console', 'migrations.tactic.super-defense')],
                [Yii::t('console', 'migrations.tactic.defense')],
                [Yii::t('console', 'migrations.tactic.normal')],
                [Yii::t('console', 'migrations.tactic.attack')],
                [Yii::t('console', 'migrations.tactic.super-attack')],
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
