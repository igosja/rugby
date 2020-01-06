<?php

use yii\db\Migration;

/**
 * Class m200107_102823_lineup_template
 */
class m200107_102823_lineup_template extends Migration
{
    const TABLE = '{{%lineup_template}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'lineup_template_id' => $this->primaryKey(11),
            'lineup_template_captain' => $this->integer(11)->defaultValue(0),
            'lineup_template_name' => $this->string(255),
            'lineup_template_national_id' => $this->integer(5)->defaultValue(0),
            'lineup_template_player_1' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_2' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_3' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_4' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_5' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_6' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_7' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_8' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_9' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_10' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_11' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_12' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_13' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_14' => $this->integer(11)->defaultValue(0),
            'lineup_template_player_15' => $this->integer(11)->defaultValue(0),
            'lineup_template_rudeness_id' => $this->integer(1)->defaultValue(0),
            'lineup_template_style_id' => $this->integer(1)->defaultValue(0),
            'lineup_template_tactic_id' => $this->integer(1)->defaultValue(0),
            'lineup_template_team_id' => $this->integer(3)->defaultValue(0),
        ]);

        $this->createIndex('lineup_template_national_id', self::TABLE, 'lineup_template_national_id');
        $this->createIndex('lineup_template_team_id', self::TABLE, 'lineup_template_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
