<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_103004_loan_position
 * @package console\migrations
 */
class m200107_103004_loan_position extends Migration
{
    private const TABLE = '{{%loan_position}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'loan_position_id' => $this->primaryKey(11),
            'loan_position_loan_id' => $this->integer(11)->defaultValue(0),
            'loan_position_position_id' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('loan_position_loan_id', self::TABLE, 'loan_position_loan_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
