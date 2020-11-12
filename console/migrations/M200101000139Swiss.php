<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000139Swiss
 * @package console\migrations
 */
class M200101000139Swiss extends Migration
{
    private const TABLE = '{{%swiss}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'guest' => $this->integer(2)->defaultValue(0),
                'home' => $this->integer(2)->defaultValue(0),
                'place' => $this->integer(11)->defaultValue(0),
                'team_id' => $this->integer(11)->unique(),
            ]
        );

        $this->addForeignKey('swiss_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
