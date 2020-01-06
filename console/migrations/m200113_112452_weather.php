<?php

use yii\db\Migration;

/**
 * Class m200113_112452_weather
 */
class m200113_112452_weather extends Migration
{
    const TABLE = '{{%weather}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'weather_id' => $this->primaryKey(1),
            'weather_name' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['weather_name'], [
            ['very hot'],
            ['hot'],
            ['sunny'],
            ['cloudy'],
            ['rain'],
            ['snow'],
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
