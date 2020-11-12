<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000149WorldCup
 * @package console\migrations
 */
class M200101000149WorldCup extends Migration
{
    private const TABLE = '{{%world_cup}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'bonus_loose' => $this->integer(2)->defaultValue(0),
                'bonus_tries' => $this->integer(2)->defaultValue(0),
                'difference' => $this->integer(3)->defaultValue(0),
                'division_id' => $this->integer(1)->notNull(),
                'draw' => $this->integer(2)->defaultValue(0),
                'game' => $this->integer(2)->defaultValue(0),
                'loose' => $this->integer(2)->defaultValue(0),
                'national_id' => $this->integer(3)->notNull(),
                'national_type_id' => $this->integer(1)->notNull(),
                'place' => $this->integer(2)->notNull(),
                'point' => $this->integer(2)->defaultValue(0),
                'point_against' => $this->integer(4)->defaultValue(0),
                'point_for' => $this->integer(4)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
                'tries_against' => $this->integer(3)->defaultValue(0),
                'tries_for' => $this->integer(3)->defaultValue(0),
                'win' => $this->integer(2)->defaultValue(0),
            ]
        );

        $this->addForeignKey('world_cup_division_id', self::TABLE, 'division_id', '{{%division}}', 'id');
        $this->addForeignKey('world_cup_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('world_cup_national_type_id', self::TABLE, 'national_type_id', '{{%national_type}}', 'id');
        $this->addForeignKey('world_cup_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');

        $this->createIndex(
            'national_division_season',
            self::TABLE,
            ['national_id', 'division_id', 'season_id'],
            true
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
