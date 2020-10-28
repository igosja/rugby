<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000034Player
 * @package console\migrations
 */
class M200101000034Player extends Migration
{
    private const TABLE = '{{%player}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'age' => $this->integer(2)->notNull(),
                'country_id' => $this->integer(3)->notNull(),
                'date_no_action' => $this->integer(11),
                'game_row' => $this->integer(2)->defaultValue(-1),
                'game_row_old' => $this->integer(2)->defaultValue(-1),
                'injury_day' => $this->integer(1)->defaultValue(0),
                'is_injury' => $this->boolean()->defaultValue(false),
                'is_no_deal' => $this->boolean()->defaultValue(false),
                'loan_day' => $this->integer(1)->defaultValue(0),
                'loan_team_id' => $this->integer(11),
                'mood_id' => $this->integer(1),
                'name_id' => $this->integer(11)->notNull(),
                'national_id' => $this->integer(3),
                'national_squad_id' => $this->integer(1),
                'order' => $this->integer(3)->defaultValue(0),
                'physical_id' => $this->integer(2)->notNull(),
                'power_nominal' => $this->integer(3)->defaultValue(0),
                'power_nominal_s' => $this->integer(3)->defaultValue(0),
                'power_old' => $this->integer(3)->defaultValue(0),
                'power_real' => $this->integer(3)->defaultValue(0),
                'price' => $this->integer(11)->defaultValue(0),
                'salary' => $this->integer(11)->defaultValue(0),
                'school_team_id' => $this->integer(11)->notNull(),
                'squad_id' => $this->integer(1),
                'style_id' => $this->integer(1)->notNull(),
                'surname_id' => $this->integer(11)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
                'tire' => $this->integer(2)->defaultValue(0),
                'training_ability' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('player_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey('player_loan_team_id', self::TABLE, 'loan_team_id', '{{%team}}', 'id');
        $this->addForeignKey('player_mood_id', self::TABLE, 'mood_id', '{{%mood}}', 'id');
        $this->addForeignKey('player_name_id', self::TABLE, 'name_id', '{{%name}}', 'id');
        $this->addForeignKey('player_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('player_national_squad_id', self::TABLE, 'national_squad_id', '{{%squad}}', 'id');
        $this->addForeignKey('player_physical_id', self::TABLE, 'physical_id', '{{%physical}}', 'id');
        $this->addForeignKey('player_school_team_id', self::TABLE, 'school_team_id', '{{%team}}', 'id');
        $this->addForeignKey('player_squad_id', self::TABLE, 'squad_id', '{{%squad}}', 'id');
        $this->addForeignKey('player_style_id', self::TABLE, 'style_id', '{{%style}}', 'id');
        $this->addForeignKey('player_surname_id', self::TABLE, 'surname_id', '{{%surname}}', 'id');
        $this->addForeignKey('player_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
