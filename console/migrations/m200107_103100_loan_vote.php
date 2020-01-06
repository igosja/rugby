<?php

use yii\db\Migration;

/**
 * Class m200107_103100_loan_vote
 */
class m200107_103100_loan_vote extends Migration
{
    const TABLE = '{{%loan_vote}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'loan_vote_id' => $this->primaryKey(11),
            'loan_vote_loan_id' => $this->integer(11)->defaultValue(0),
            'loan_vote_rating' => $this->integer(2)->defaultValue(0),
            'loan_vote_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('loan_vote_loan_id', self::TABLE, 'loan_vote_loan_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
