<?php

// TODO refactor

namespace console\migrations;

use Yii;
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
                'game' => $this->integer(5)->defaultValue(0),
                'stadium_capacity' => $this->integer(5)->defaultValue(0),
            ]
        );

        $this->addForeignKey('federation_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');

        $this->insert(self::TABLE, ['country_id' => 0]);

        $this->update(self::TABLE, ['id' => 0], ['id' => 1]);

        Yii::$app->db->createCommand('ALTER TABLE ' . self::TABLE . ' AUTO_INCREMENT=1')->execute();

        $data = [];
        for ($i = 1; $i <= self::MAX_COUNTRY_ID; $i++) {
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
