<?php

use yii\db\Migration;

/**
 * Class m200107_110003_squad
 */
class m200107_110003_squad extends Migration
{
    const TABLE = '{{%squad}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'squad_id' => $this->primaryKey(1),
            'squad_color' => $this->char(6),
            'squad_name' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['squad_color', 'squad_name'], [
            ['', '------'],
            ['DFF2BF', '1 состав'],
            ['C9FFCC', '2 состав'],
            ['FEEFB3', '3 состав'],
            ['FFBABA', '4 состав'],
            ['E0E0E0', '5 состав'],
            ['E0E0E0', '6 состав'],
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
