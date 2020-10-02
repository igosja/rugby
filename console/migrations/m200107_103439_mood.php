<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_103439_mood
 * @package console\migrations
 */
class m200107_103439_mood extends Migration
{
    private const TABLE = '{{%mood}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'mood_id' => $this->primaryKey(1),
            'mood_name' => $this->string(10),
        ]);

        $this->batchInsert(self::TABLE, ['mood_name'], [
            ['super'],
            ['normal'],
            ['rest'],
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
