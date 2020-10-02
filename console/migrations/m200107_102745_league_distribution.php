<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_102745_league_distribution
 * @package console\migrations
 */
class m200107_102745_league_distribution extends Migration
{
    private const TABLE = '{{%league_distribution}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'league_distribution_id' => $this->primaryKey(11),
            'league_distribution_country_id' => $this->integer(3)->defaultValue(0),
            'league_distribution_group' => $this->integer(1)->defaultValue(0),
            'league_distribution_qualification_3' => $this->integer(1)->defaultValue(0),
            'league_distribution_qualification_2' => $this->integer(1)->defaultValue(0),
            'league_distribution_qualification_1' => $this->integer(1)->defaultValue(0),
            'league_distribution_season_id' => $this->integer(3)->defaultValue(0),
        ]);

        $this->createIndex('league_distribution_country_id', self::TABLE, 'league_distribution_country_id');
        $this->createIndex('league_distribution_season_id', self::TABLE, 'league_distribution_season_id');

        $this->batchInsert(self::TABLE, [
            'league_distribution_country_id',
            'league_distribution_group',
            'league_distribution_qualification_3',
            'league_distribution_qualification_2',
            'league_distribution_qualification_1',
            'league_distribution_season_id'
        ], [
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
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
