<?php

use yii\db\Migration;

/**
 * Class m200107_105654_season
 */
class m200107_105654_season extends Migration
{
    const TABLE = '{{%season}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'season_id' => $this->primaryKey(3),
        ]);

        $this->insert(self::TABLE, [
            'season_id' => 1,
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
