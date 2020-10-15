<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000125RatingCountry
 * @package console\migrations
 */
class M200101000125RatingCountry extends Migration
{
    private const TABLE = '{{%rating_country}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'auto_place' => $this->integer(3)->defaultValue(0),
                'country_id' => $this->integer(3)->notNull(),
                'league_place' => $this->integer(3)->defaultValue(0),
                'stadium_place' => $this->integer(3)->defaultValue(0),
            ]
        );

        $this->addForeignKey('rating_country_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');

        return true;
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
