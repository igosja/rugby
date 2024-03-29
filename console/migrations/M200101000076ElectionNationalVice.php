<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000076ElectionNationalVice
 * @package console\migrations
 */
class M200101000076ElectionNationalVice extends Migration
{
    private const TABLE = '{{%election_national_vice}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'federation_id' => $this->integer(3)->notNull(),
                'date' => $this->integer(11)->notNull(),
                'election_status_id' => $this->integer(1)->notNull(),
                'national_type_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('election_national_vice_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey(
            'election_national_vice_election_status_id',
            self::TABLE,
            'election_status_id',
            '{{%election_status}}',
            'id'
        );
        $this->addForeignKey(
            'election_national_vice_national_type_id',
            self::TABLE,
            'national_type_id',
            '{{%national_type}}',
            'id'
        );

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
