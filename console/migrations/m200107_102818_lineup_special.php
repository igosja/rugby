<?php

use yii\db\Migration;

/**
 * Class m200107_102818_lineup_special
 */
class m200107_102818_lineup_special extends Migration
{
    const TABLE = '{{%lineup_special}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'lineup_special_id' => $this->primaryKey(11),
            'lineup_special_level' => $this->integer(1)->defaultValue(0),
            'lineup_special_lineup_id' => $this->integer(11)->defaultValue(0),
            'lineup_special_special_id' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('lineup_special_lineup_id', self::TABLE, 'lineup_special_lineup_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
