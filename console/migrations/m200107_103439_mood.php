<?php

use yii\db\Migration;

/**
 * Class m200107_103439_mood
 */
class m200107_103439_mood extends Migration
{
    const TABLE = '{{%mood}}';

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
