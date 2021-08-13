<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000022FriendlyStatus
 * @package console\migrations
 */
class M200101000022FriendlyStatus extends Migration
{
    private const TABLE = '{{%friendly_status}}';

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
                [Yii::t('console', 'migrations.friendly-status.all')],
                [Yii::t('console', 'migrations.friendly-status.self')],
                [Yii::t('console', 'migrations.friendly-status.none')],
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
