<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000073ElectionNationalApplication
 * @package console\migrations
 */
class M200101000073ElectionNationalApplication extends Migration
{
    private const TABLE = '{{%election_national_application}}';

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
                'election_national_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'election_national_application_election_national_id',
            self::TABLE,
            'election_national_id',
            '{{%election_national}}',
            'id'
        );
        $this->addForeignKey('election_national_application_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('election_user', self::TABLE, ['election_national_id', 'user_id'], true);

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
