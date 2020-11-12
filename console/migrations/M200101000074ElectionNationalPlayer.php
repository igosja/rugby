<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000074ElectionNationalPlayer
 * @package console\migrations
 */
class M200101000074ElectionNationalPlayer extends Migration
{
    private const TABLE = '{{%election_national_player}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'election_national_application_id' => $this->integer(11)->notNull(),
                'player_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'election_national_player_election_national_application_id',
            self::TABLE,
            'election_national_application_id',
            '{{%election_national_application}}',
            'id'
        );
        $this->addForeignKey('election_national_player_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');

        $this->createIndex('election_player', self::TABLE, ['election_national_application_id', 'player_id'], true);

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
