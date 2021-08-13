<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000081ElectionPresidentApplication
 * @package console\migrations
 */
class M200101000081ElectionPresidentApplication extends Migration
{
    private const TABLE = '{{%election_president_application}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->notNull(),
                'election_president_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'election_president_application_election_president_id',
            self::TABLE,
            'election_president_id',
            '{{%election_president}}',
            'id'
        );
        $this->addForeignKey('election_president_application_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('election_user', self::TABLE, ['election_president_id', 'user_id'], true);

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