<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000055Loan
 * @package console\migrations
 */
class M200101000055Loan extends Migration
{
    private const TABLE = '{{%loan}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'age' => $this->integer(2)->defaultValue(0),
                'cancel' => $this->integer(11)->defaultValue(0),
                'date' => $this->integer(11)->notNull(),
                'day' => $this->integer(1),
                'day_max' => $this->integer(1)->notNull(),
                'day_min' => $this->integer(1)->notNull(),
                'player_id' => $this->integer(11)->notNull(),
                'player_price' => $this->integer(11)->defaultValue(0),
                'power' => $this->integer(3)->defaultValue(0),
                'price_buyer' => $this->integer(11)->defaultValue(0),
                'price_seller' => $this->integer(11)->notNull(),
                'ready' => $this->integer(11),
                'season_id' => $this->integer(3)->notNull(),
                'team_buyer_id' => $this->integer(11),
                'team_seller_id' => $this->integer(11)->notNull(),
                'user_buyer_id' => $this->integer(11),
                'user_seller_id' => $this->integer(11)->notNull(),
                'voted' => $this->integer(11),
            ]
        );

        $this->addForeignKey('loan_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('loan_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('loan_team_buyer_id', self::TABLE, 'team_buyer_id', '{{%team}}', 'id');
        $this->addForeignKey('loan_team_seller_id', self::TABLE, 'team_seller_id', '{{%team}}', 'id');
        $this->addForeignKey('loan_user_buyer_id', self::TABLE, 'user_buyer_id', '{{%user}}', 'id');
        $this->addForeignKey('loan_user_seller_id', self::TABLE, 'user_seller_id', '{{%user}}', 'id');

        $this->createIndex('ready', self::TABLE, 'ready');
        $this->createIndex('voted', self::TABLE, 'voted');

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown():bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
