<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000071ElectionStatus
 * @package console\migrations
 */
class M200101000071ElectionStatus extends Migration
{
    private const TABLE = '{{%election_status}}';

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
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                [Yii::t('console', 'migrations.election-status.candidates')],
                [Yii::t('console', 'migrations.election-status.open')],
                [Yii::t('console', 'migrations.election-status.close')],
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
