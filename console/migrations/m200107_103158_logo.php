<?php

use yii\db\Migration;

/**
 * Class m200107_103158_logo
 */
class m200107_103158_logo extends Migration
{
    const TABLE = '{{%logo}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'logo_id' => $this->primaryKey(11),
            'logo_date' => $this->integer(11)->defaultValue(0),
            'logo_team_id' => $this->integer(11)->defaultValue(0),
            'logo_text' => $this->text(),
            'logo_user_id' => $this->integer(11)->defaultValue(0),
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
