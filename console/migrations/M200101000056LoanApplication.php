<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000056LoanApplication
 * @package console\migrations
 */
class M200101000056LoanApplication extends Migration
{
    private const TABLE = '{{%loan_application}}';

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
                'day' => $this->integer(1)->notNull(),
                'deal_reason_id' => $this->integer(2),
                'is_only_one' => $this->boolean()->defaultValue(false),
                'loan_id' => $this->integer(11)->notNull(),
                'price' => $this->integer(11)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'loan_application_deal_reason_id',
            self::TABLE,
            'deal_reason_id',
            '{{%deal_reason}}',
            'id'
        );
        $this->addForeignKey('loan_application_loan_id', self::TABLE, 'loan_id', '{{%loan}}', 'id');
        $this->addForeignKey('loan_application_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('loan_application_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('loan_team', self::TABLE, ['loan_id', 'team_id'], true);

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
