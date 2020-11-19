<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000134StatisticType
 * @package console\migrations
 */
class M200101000134StatisticType extends Migration
{
    private const TABLE = '{{%statistic_type}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(255)->notNull(),
                'select_field' => $this->string(255)->notNull(),
                'statistic_chapter_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey(
            'statistic_type_statistic_chapter_id',
            self::TABLE,
            'statistic_chapter_id',
            '{{%statistic_chapter}}',
            'id'
        );

        $this->batchInsert(
            self::TABLE,
            [
                'name',
                'select_field',
                'statistic_chapter_id',
            ],
            [
                ['Points', 'point', 1],
                ['Tries', 'try', 1],
                ['Metres gained', 'metre_gained', 1],
                ['Carries', 'carry', 1],
                ['Clean breaks', 'clean_break', 1],
                ['Defenders beaten', 'defender_beaten', 1],
                ['Passes', 'pass', 1],
                ['Tackles', 'tackle', 1],
                ['Turnover won', 'turnover_won', 1],
                ['Penalty conceded', 'penalty_conceded', 1],
                ['Drop goals', 'drop_goal', 1],
                ['Yellow cards', 'yellow_card', 1],
                ['Red cards', 'red_card', 1],
                ['Games', 'game', 1],
                ['Won', 'win', 1],
                ['Drawn', 'draw', 1],
                ['Lost', 'loose', 1],
                ['Points', 'point', 2],
                ['Tries', 'try', 2],
                ['Metres gained', 'metre_gained', 2],
                ['Clean breaks', 'clean_break', 2],
                ['Defenders beaten', 'defender_beaten', 2],
                ['Passes', 'pass', 2],
                ['Tackles', 'tackle', 2],
                ['Turnover won', 'turnover_won', 2],
                ['Yellow cards', 'yellow', 2],
                ['Red cards', 'red', 2],
                ['Games', 'game', 2],
                ['Won', 'win', 2],
                ['Drawn', 'draw', 2],
                ['Lost', 'loose', 2],
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
