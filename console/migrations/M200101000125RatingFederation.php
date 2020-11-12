<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000125RatingFederation
 * @package console\migrations
 */
class M200101000125RatingFederation extends Migration
{
    private const TABLE = '{{%rating_federation}}';

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
                'federation_id' => $this->integer(3)->notNull(),
                'league_place' => $this->integer(3)->defaultValue(0),
                'stadium_place' => $this->integer(3)->defaultValue(0),
            ]
        );

        $this->addForeignKey('rating_federation_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');

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
