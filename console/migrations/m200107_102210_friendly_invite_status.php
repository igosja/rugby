<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_102210_friendly_invite_status
 * @package console\migrations
 */
class m200107_102210_friendly_invite_status extends Migration
{
    private const TABLE = '{{%friendly_invite_status}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'friendly_invite_status_id' => $this->primaryKey(1),
            'friendly_invite_status_name' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['friendly_invite_status_name'], [
            ['Новое приглашение'],
            ['Приглашение принято'],
            ['Приглашение отклонено'],
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
