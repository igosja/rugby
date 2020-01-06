<?php

use yii\db\Migration;

/**
 * Class m200107_105604_school
 */
class m200107_105604_school extends Migration
{
    const TABLE = '{{%school}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'school_id' => $this->primaryKey(11),
            'school_day' => $this->integer(2)->defaultValue(0),
            'school_position_id' => $this->integer(1)->defaultValue(0),
            'school_ready' => $this->integer(11)->defaultValue(0),
            'school_season_id' => $this->integer(3)->defaultValue(0),
            'school_special_id' => $this->integer(2)->defaultValue(0),
            'school_style_id' => $this->integer(1)->defaultValue(0),
            'school_team_id' => $this->integer(11)->defaultValue(0),
            'school_with_special' => $this->integer(1)->defaultValue(0),
            'school_with_special_request' => $this->integer(1)->defaultValue(0),
            'school_with_style' => $this->integer(1)->defaultValue(0),
            'school_with_style_request' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('school_ready', self::TABLE, 'school_ready');
        $this->createIndex('school_season_id', self::TABLE, 'school_season_id');
        $this->createIndex('school_team_id', self::TABLE, 'school_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
