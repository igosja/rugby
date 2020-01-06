<?php

use yii\db\Migration;

/**
 * Class m200107_105355_recommendation
 */
class m200107_105355_recommendation extends Migration
{
    const TABLE = '{{%recommendation}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'recommendation_id' => $this->primaryKey(11),
            'recommendation_team_id' => $this->integer(11)->defaultValue(0),
            'recommendation_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('recommendation_team_id', self::TABLE, 'recommendation_team_id');
        $this->createIndex('recommendation_user_id', self::TABLE, 'recommendation_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
