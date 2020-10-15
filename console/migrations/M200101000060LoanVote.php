<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000060LoanVote
 * @package console\migrations
 */
class M200101000060LoanVote extends Migration
{
    private const TABLE = '{{%loan_vote}}';

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
                'rating' => $this->integer(2)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('loan_vote_loan_id', self::TABLE, 'loan_id', '{{%loan}}', 'id');
        $this->addForeignKey('loan_vote_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('loan_user', self::TABLE, ['loan_id', 'user_id'], true);

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
