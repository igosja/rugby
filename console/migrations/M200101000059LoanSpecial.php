<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000059LoanSpecial
 * @package console\migrations
 */
class M200101000059LoanSpecial extends Migration
{
    private const TABLE = '{{%loan_special}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'level' => $this->integer(1)->notNull(),
                'loan_id' => $this->integer(11)->notNull(),
                'special_id' => $this->integer(2)->notNull(),
            ]
        );

        $this->addForeignKey('loan_special_loan_id', self::TABLE, 'loan_id', '{{%loan}}', 'id');
        $this->addForeignKey('loan_special_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');

        $this->createIndex('loan_special', self::TABLE, ['loan_id', 'special_id'], true);

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
