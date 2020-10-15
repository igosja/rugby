<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000058LoanPosition
 * @package console\migrations
 */
class M200101000058LoanPosition extends Migration
{
    private const TABLE = '{{%loan_position}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'loan_id' => $this->integer(11)->notNull(),
                'position_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('loan_position_loan_id', self::TABLE, 'loan_id', '{{%loan}}', 'id');
        $this->addForeignKey('loan_position_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');

        $this->createIndex('loan_position', self::TABLE, ['loan_id', 'position_id'], true);

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
