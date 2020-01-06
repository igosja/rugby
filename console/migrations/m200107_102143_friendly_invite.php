<?php

use yii\db\Migration;

/**
 * Class m200107_102143_friendly_invite
 */
class m200107_102143_friendly_invite extends Migration
{
    const TABLE = '{{%friendly_invite}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'friendly_invite_id' => $this->primaryKey(11),
            'friendly_invite_date' => $this->integer(11)->defaultValue(0),
            'friendly_invite_friendly_invite_status_id' => $this->integer(1)->defaultValue(0),
            'friendly_invite_guest_team_id' => $this->integer(11)->defaultValue(0),
            'friendly_invite_guest_user_id' => $this->integer(11)->defaultValue(0),
            'friendly_invite_home_team_id' => $this->integer(11)->defaultValue(0),
            'friendly_invite_home_user_id' => $this->integer(11)->defaultValue(0),
            'friendly_invite_schedule_id' => $this->integer(11)->defaultValue(0),
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
