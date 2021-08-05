<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000128Recommendation
 * @package console\migrations
 */
class M200101000128Recommendation extends Migration
{
    private const TABLE = '{{%recommendation}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'team_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('recommendation_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('recommendation_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
