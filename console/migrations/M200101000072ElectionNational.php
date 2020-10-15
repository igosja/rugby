<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000072ElectionNational
 * @package console\migrations
 */
class M200101000072ElectionNational extends Migration
{
    private const TABLE = '{{%election_national}}';

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
                'national_type_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('election_national_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey(
            'election_national_election_status_id',
            self::TABLE,
            'election_status_id',
            '{{%election_status}}',
            'id'
        );
        $this->addForeignKey(
            'election_national_national_type_id',
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
