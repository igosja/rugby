<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000020Federation
 * @package console\migrations
 */
class M200101000020Federation extends Migration
{
    private const TABLE = '{{%federation}}';
    private const MAX_COUNTRY_ID = 197;

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(3),
                'auto' => $this->integer(5)->defaultValue(0),
                'country_id' => $this->integer(3)->unique(),
                'finance' => $this->integer(11)->defaultValue(0),
                'game' => $this->integer(5)->defaultValue(0),
                'president_user_id' => $this->integer(11)->defaultValue(0),
                'stadium_capacity' => $this->integer(5)->defaultValue(0),
                'vice_user_id' => $this->integer(11)->defaultValue(0),
            ]
        );

        $this->addForeignKey('federation_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey('federation_president_user_id', self::TABLE, 'president_user_id', '{{%user}}', 'id');
        $this->addForeignKey('federation_vice_user_id', self::TABLE, 'vice_user_id', '{{%user}}', 'id');

        $data = [];
        for ($i = 0; $i <= self::MAX_COUNTRY_ID; $i++) {
            $data[] = [$i];
        }
        $this->batchInsert(self::TABLE, ['country_id'], $data);

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}