<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_105355_recommendation
 * @package console\migrations
 */
class m200107_105355_recommendation extends Migration
{
    private const TABLE = '{{%recommendation}}';

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
