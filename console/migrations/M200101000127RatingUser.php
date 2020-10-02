<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000127RatingUser
 * @package console\migrations
 */
class M200101000127RatingUser extends Migration
{
    private const TABLE = '{{%rating_user}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'rating_place' => $this->integer(11)->defaultValue(0),
                'user_id' => $this->integer(11)->defaultValue(0),
            ]
        );

        $this->addForeignKey('rating_user_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
