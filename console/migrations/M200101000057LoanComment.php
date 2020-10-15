<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000057LoanComment
 * @package console\migrations
 */
class M200101000057LoanComment extends Migration
{
    private const TABLE = '{{%loan_comment}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'check' => $this->integer(11)->defaultValue(0),
                'date' => $this->integer(11)->defaultValue(0),
                'loan_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('loan_comment_loan_id', self::TABLE, 'loan_id', '{{%loan}}', 'id');
        $this->addForeignKey('loan_comment_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('check', self::TABLE, 'check');

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
