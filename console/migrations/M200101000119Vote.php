<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000119Vote
 * @package console\migrations
 */
class M200101000119Vote extends Migration
{
    private const TABLE = '{{%vote}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'federation_id' => $this->integer(3),
                'date' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
                'vote_status_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('vote_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('vote_vote_status_id', self::TABLE, 'vote_status_id', '{{%vote_status}}', 'id');
        $this->addForeignKey('vote_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
