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
                'country_id' => $this->integer(3)->notNull(),
                'date' => $this->integer(11)->defaultValue(0),
                'election_status_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('election_president_vice_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
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
