<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000099LeagueDistribution
 * @package console\migrations
 */
class M200101000099LeagueDistribution extends Migration
{
    private const TABLE = '{{%league_distribution}}';

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
                'group' => $this->integer(1)->defaultValue(0),
                'qualification_3' => $this->integer(1)->defaultValue(0),
                'qualification_2' => $this->integer(1)->defaultValue(0),
                'qualification_1' => $this->integer(1)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('league_distribution_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('league_distribution_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');

        $this->createIndex('federation_season', self::TABLE, ['federation_id', 'season_id'], true);

        $this->batchInsert(
            self::TABLE,
            [
                'federation_id',
                'group',
                'qualification_3',
                'qualification_2',
                'qualification_1',
                'season_id'
            ],
            [
                [164, 2, 2, 1, 0, 2],
                [54, 2, 2, 1, 0, 2],
                [124, 2, 2, 1, 0, 2],
                [194, 2, 2, 1, 0, 2],
                [81, 2, 2, 1, 0, 2],
                [9, 2, 2, 1, 0, 2],
                [61, 2, 2, 0, 1, 2],
                [154, 2, 2, 0, 1, 2],
                [7, 2, 2, 0, 1, 2],
                [83, 2, 2, 0, 1, 2],
            ]
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
