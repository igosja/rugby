<?php

use yii\db\Migration;

/**
 * Class m200107_111212_style
 */
class m200107_111212_style extends Migration
{
    const TABLE = '{{%style}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'style_id' => $this->primaryKey(1),
            'style_name' => $this->string(15),
        ]);

        $this->batchInsert(self::TABLE, ['style_name'], [
            ['normal'],
            ['down the middle'],
            ['champagne rugby'],
            ['15 man rugby'],
            ['10 man rugby'],
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
