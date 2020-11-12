<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000051Weather
 * @package console\migrations
 */
class M200101000051Weather extends Migration
{
    private const TABLE = '{{%weather}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(20)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['very hot'],
                ['hot'],
                ['sunny'],
                ['cloudy'],
                ['rain'],
                ['snow'],
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
