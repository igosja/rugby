<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000145UserBlock
 * @package console\migrations
 */
class M200101000145UserBlock extends Migration
{
    private const TABLE = '{{%user_block}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->notNull(),
                'user_block_reason_id' => $this->integer(2)->notNull(),
                'user_block_type_id' => $this->integer(1)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'user_block_user_block_reason_id',
            self::TABLE,
            'user_block_reason_id',
            '{{%user_block_reason}}',
            'id'
        );
        $this->addForeignKey(
            'user_block_user_block_type_id',
            self::TABLE,
            'user_block_type_id',
            '{{%user_block_type}}',
            'id'
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
