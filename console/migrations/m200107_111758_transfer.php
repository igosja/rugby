<?php

use yii\db\Migration;

/**
 * Class m200107_111758_transfer
 */
class m200107_111758_transfer extends Migration
{
    const TABLE = '{{%transfer}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'transfer_id' => $this->primaryKey(11),
            'transfer_age' => $this->integer(2)->defaultValue(0),
            'transfer_cancel' => $this->integer(11)->defaultValue(0),
            'transfer_checked' => $this->integer(11)->defaultValue(0),
            'transfer_date' => $this->integer(11)->defaultValue(0),
            'transfer_player_id' => $this->integer(11)->defaultValue(0),
            'transfer_player_price' => $this->integer(11)->defaultValue(0),
            'transfer_power' => $this->integer(3)->defaultValue(0),
            'transfer_price_buyer' => $this->integer(11)->defaultValue(0),
            'transfer_price_seller' => $this->integer(11)->defaultValue(0),
            'transfer_ready' => $this->integer(11)->defaultValue(0),
            'transfer_season_id' => $this->integer(3)->defaultValue(0),
            'transfer_team_buyer_id' => $this->integer(11)->defaultValue(0),
            'transfer_team_seller_id' => $this->integer(11)->defaultValue(0),
            'transfer_to_league' => $this->integer(1)->defaultValue(0),
            'transfer_user_buyer_id' => $this->integer(11)->defaultValue(0),
            'transfer_user_seller_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('transfer_player_id', self::TABLE, 'transfer_player_id');
        $this->createIndex('transfer_ready', self::TABLE, 'transfer_ready');
        $this->createIndex('transfer_team_buyer_id', self::TABLE, 'transfer_team_buyer_id');
        $this->createIndex('transfer_team_seller_id', self::TABLE, 'transfer_team_seller_id');
        $this->createIndex('transfer_user_buyer_id', self::TABLE, 'transfer_user_buyer_id');
        $this->createIndex('transfer_user_seller_id', self::TABLE, 'transfer_user_seller_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
