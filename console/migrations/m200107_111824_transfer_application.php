<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111824_transfer_application
 * @package console\migrations
 */
class m200107_111824_transfer_application extends Migration
{
    private const TABLE = '{{%transfer_application}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'transfer_application_id' => $this->primaryKey(11),
            'transfer_application_date' => $this->integer(11)->defaultValue(0),
            'transfer_application_deal_reason_id' => $this->integer(2)->defaultValue(0),
            'transfer_application_only_one' => $this->integer(1)->defaultValue(0),
            'transfer_application_price' => $this->integer(11)->defaultValue(0),
            'transfer_application_team_id' => $this->integer(11)->defaultValue(0),
            'transfer_application_transfer_id' => $this->integer(11)->defaultValue(0),
            'transfer_application_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('transfer_application_transfer_id', self::TABLE, 'transfer_application_transfer_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
