<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000063TransferApplication
 * @package console\migrations
 */
class M200101000063TransferApplication extends Migration
{
    private const TABLE = '{{%transfer_application}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->defaultValue(0),
                'deal_reason_id' => $this->integer(2),
                'is_only_one' => $this->boolean(),
                'price' => $this->integer(11)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
                'transfer_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'transfer_application_deal_reason_id',
            self::TABLE,
            'deal_reason_id',
            '{{%deal_reason}}',
            'id'
        );
        $this->addForeignKey('transfer_application_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('transfer_application_transfer_id', self::TABLE, 'transfer_id', '{{%transfer}}', 'id');
        $this->addForeignKey('transfer_application_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('transfer_team', self::TABLE, ['transfer_id', 'team_id'], true);

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
