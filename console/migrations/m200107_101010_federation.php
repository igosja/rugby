<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_101010_federation
 * @package console\migrations
 */
class m200107_101010_federation extends Migration
{
    private const TABLE = '{{%federation}}';
    private const MAX_COUNTRY_ID = 197;

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'federation_id' => $this->primaryKey(3),
            'federation_auto' => $this->integer(5)->defaultValue(0),
            'federation_country_id' => $this->integer(3)->defaultValue(0),
            'federation_finance' => $this->integer(11)->defaultValue(0),
            'federation_game' => $this->integer(5)->defaultValue(0),
            'federation_president_id' => $this->integer(11)->defaultValue(0),
            'federation_stadium_capacity' => $this->integer(5)->defaultValue(0),
            'federation_vice_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('federation_country_id', self::TABLE, 'federation_country_id');

        $data = [];
        for ($i = 0; $i <= self::MAX_COUNTRY_ID; $i++) {
            $data[] = [$i];
        }
        $this->batchInsert(self::TABLE, ['federation_country_id'], $data);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
