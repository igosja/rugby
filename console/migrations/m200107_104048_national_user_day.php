<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104048_national_user_day
 * @package console\migrations
 */
class m200107_104048_national_user_day extends Migration
{
    private const TABLE = '{{%national_user_day}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'national_user_day_id' => $this->primaryKey(11),
            'national_user_day_day' => $this->integer(3)->defaultValue(0),
            'national_user_day_national_id' => $this->integer(3)->defaultValue(0),
            'national_user_day_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('national_user_day_national_id', self::TABLE, 'national_user_day_national_id');
        $this->createIndex('national_user_day_user_id', self::TABLE, 'national_user_day_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
