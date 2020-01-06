<?php

use yii\db\Migration;

/**
 * Class m200107_102835_loan
 */
class m200107_102835_loan extends Migration
{
    const TABLE = '{{%loan}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'loan_id' => $this->primaryKey(11),
            'loan_age' => $this->integer(2)->defaultValue(0),
            'loan_cancel' => $this->integer(11)->defaultValue(0),
            'loan_checked' => $this->integer(11)->defaultValue(0),
            'loan_date' => $this->integer(11)->defaultValue(0),
            'loan_day' => $this->integer(1)->defaultValue(0),
            'loan_day_max' => $this->integer(1)->defaultValue(0),
            'loan_day_min' => $this->integer(1)->defaultValue(0),
            'loan_player_id' => $this->integer(11)->defaultValue(0),
            'loan_player_price' => $this->integer(11)->defaultValue(0),
            'loan_power' => $this->integer(3)->defaultValue(0),
            'loan_price_buyer' => $this->integer(11)->defaultValue(0),
            'loan_price_seller' => $this->integer(11)->defaultValue(0),
            'loan_ready' => $this->integer(11)->defaultValue(0),
            'loan_season_id' => $this->integer(3)->defaultValue(0),
            'loan_team_buyer_id' => $this->integer(11)->defaultValue(0),
            'loan_team_seller_id' => $this->integer(11)->defaultValue(0),
            'loan_user_buyer_id' => $this->integer(11)->defaultValue(0),
            'loan_user_seller_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('loan_player_id', self::TABLE, 'loan_player_id');
        $this->createIndex('loan_ready', self::TABLE, 'loan_ready');
        $this->createIndex('loan_team_buyer_id', self::TABLE, 'loan_team_buyer_id');
        $this->createIndex('loan_team_seller_id', self::TABLE, 'loan_team_seller_id');
        $this->createIndex('loan_user_buyer_id', self::TABLE, 'loan_user_buyer_id');
        $this->createIndex('loan_user_seller_id', self::TABLE, 'loan_user_seller_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
