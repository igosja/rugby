<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_102906_loan_application
 * @package console\migrations
 */
class m200107_102906_loan_application extends Migration
{
    private const TABLE = '{{%loan_application}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'loan_application_id' => $this->primaryKey(11),
            'loan_application_date' => $this->integer(11)->defaultValue(0),
            'loan_application_day' => $this->integer(1)->defaultValue(0),
            'loan_application_deal_reason_id' => $this->integer(2)->defaultValue(0),
            'loan_application_loan_id' => $this->integer(11)->defaultValue(0),
            'loan_application_only_one' => $this->integer(1)->defaultValue(0),
            'loan_application_price' => $this->integer(11)->defaultValue(0),
            'loan_application_team_id' => $this->integer(11)->defaultValue(0),
            'loan_application_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('loan_application_loan_id', self::TABLE, 'loan_application_loan_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
