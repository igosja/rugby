<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000078ElectionNationalViceVote
 * @package console\migrations
 */
class M200101000078ElectionNationalViceVote extends Migration
{
    private const TABLE = '{{%election_national_vice_vote}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'election_national_vice_application_id' => $this->integer(11)->notNull(),
                'date' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'vote_election_national_vice_application_id',
            self::TABLE,
            'election_national_vice_application_id',
            '{{%election_national_vice_application}}',
            'id'
        );
        $this->addForeignKey('election_national_vice_vote_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('application_user', self::TABLE, ['election_national_vice_application_id', 'user_id'], true);

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
