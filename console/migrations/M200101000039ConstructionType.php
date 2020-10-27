<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000039ConstructionType
 * @package console\migrations
 */
class M200101000039ConstructionType extends Migration
{
    private const TABLE = '{{%construction_type}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(255)->notNull()->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['Construction'],
                ['Destruction'],
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
