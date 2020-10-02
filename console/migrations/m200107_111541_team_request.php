<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111541_team_ask
 * @package console\migrations
 */
class m200107_111541_team_request extends Migration
{
    private const TABLE = '{{%team_request}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'team_request_id' => $this->primaryKey(11),
            'team_request_date' => $this->integer(11)->defaultValue(0),
            'team_request_leave_id' => $this->integer(11)->defaultValue(0),
            'team_request_team_id' => $this->integer(11)->defaultValue(0),
            'team_request_user_id' => $this->integer(11)->defaultValue(0),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
