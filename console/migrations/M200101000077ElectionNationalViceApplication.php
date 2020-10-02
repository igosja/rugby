<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000077ElectionNationalViceApplication
 * @package console\migrations
 */
class M200101000077ElectionNationalViceApplication extends Migration
{
    private const TABLE = '{{%election_national_vice_application}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->defaultValue(0),
                'election_national_vice_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'election_national_vice_application_election_national_vice_id',
            self::TABLE,
            'election_national_vice_id',
            '{{%election_national_vice}}',
            'id'
        );
        $this->addForeignKey('election_national_vice_application_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('election_user', self::TABLE, ['election_national_vice_id', 'user_id'], true);

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
