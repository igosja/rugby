<?php

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
                'country_id' => $this->integer(3)->notNull(),
                'group' => $this->integer(1)->defaultValue(0),
                'qualification_3' => $this->integer(1)->defaultValue(0),
                'qualification_2' => $this->integer(1)->defaultValue(0),
                'qualification_1' => $this->integer(1)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('league_distribution_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');

        $this->createIndex('country_season', self::TABLE, ['country_id', 'season_id'], true);

        $this->batchInsert(
            self::TABLE,
            [
                'country_id',
                'group',
                'qualification_3',
                'qualification_2',
                'qualification_1',
                'season_id'
            ],
            [
                [71, 2, 1, 1, 0, 2],
                [133, 2, 1, 1, 0, 2],
                [157, 2, 1, 1, 0, 2],
                [185, 2, 1, 1, 0, 2],
                [18, 2, 1, 0, 1, 2],
                [43, 2, 1, 0, 1, 2],
                [122, 2, 1, 0, 1, 2],
                [151, 2, 1, 0, 1, 2],
                [171, 2, 1, 0, 1, 2],
                [176, 2, 1, 0, 1, 2],
                [182, 2, 1, 0, 1, 2],
                [184, 2, 1, 0, 1, 2],
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
