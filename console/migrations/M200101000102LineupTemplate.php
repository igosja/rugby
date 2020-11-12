<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000102LineupTemplate
 * @package console\migrations
 */
class M200101000102LineupTemplate extends Migration
{
    private const TABLE = '{{%lineup_template}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'is_captain' => $this->boolean()->defaultValue(false),
                'name' => $this->string(255)->notNull(),
                'national_id' => $this->integer(3),
                'player_1_id' => $this->integer(11)->notNull(),
                'player_2_id' => $this->integer(11)->notNull(),
                'player_3_id' => $this->integer(11)->notNull(),
                'player_4_id' => $this->integer(11)->notNull(),
                'player_5_id' => $this->integer(11)->notNull(),
                'player_6_id' => $this->integer(11)->notNull(),
                'player_7_id' => $this->integer(11)->notNull(),
                'player_8_id' => $this->integer(11)->notNull(),
                'player_9_id' => $this->integer(11)->notNull(),
                'player_10_id' => $this->integer(11)->notNull(),
                'player_11_id' => $this->integer(11)->notNull(),
                'player_12_id' => $this->integer(11)->notNull(),
                'player_13_id' => $this->integer(11)->notNull(),
                'player_14_id' => $this->integer(11)->notNull(),
                'player_15_id' => $this->integer(11)->notNull(),
                'rudeness_id' => $this->integer(1)->notNull(),
                'style_id' => $this->integer(1)->notNull(),
                'tactic_id' => $this->integer(1)->notNull(),
                'team_id' => $this->integer(11),
            ]
        );

        $this->addForeignKey('lineup_template_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('lineup_template_player_1_id', self::TABLE, 'player_1_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_2_id', self::TABLE, 'player_2_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_3_id', self::TABLE, 'player_3_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_4_id', self::TABLE, 'player_4_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_5_id', self::TABLE, 'player_5_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_6_id', self::TABLE, 'player_6_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_7_id', self::TABLE, 'player_7_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_8_id', self::TABLE, 'player_8_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_9_id', self::TABLE, 'player_9_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_10_id', self::TABLE, 'player_10_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_11_id', self::TABLE, 'player_11_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_12_id', self::TABLE, 'player_12_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_13_id', self::TABLE, 'player_13_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_14_id', self::TABLE, 'player_14_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_player_15_id', self::TABLE, 'player_15_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_template_rudeness_id', self::TABLE, 'rudeness_id', '{{%rudeness}}', 'id');
        $this->addForeignKey('lineup_template_style_id', self::TABLE, 'style_id', '{{%style}}', 'id');
        $this->addForeignKey('lineup_template_tactic_id', self::TABLE, 'tactic_id', '{{%tactic}}', 'id');
        $this->addForeignKey('lineup_template_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
