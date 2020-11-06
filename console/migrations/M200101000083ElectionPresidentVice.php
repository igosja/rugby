<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000083ElectionPresidentVice
 * @package console\migrations
 */
class M200101000083ElectionPresidentVice extends Migration
{
    private const TABLE = '{{%election_president_vice}}';

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
            ]
        );

        $this->addForeignKey('election_president_vice_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey(
            'election_president_vice_election_status_id',
            self::TABLE,
            'election_status_id',
            '{{%election_status}}',
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
