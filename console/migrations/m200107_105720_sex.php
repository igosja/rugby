<?php

use yii\db\Migration;

/**
 * Class m200107_105720_sex
 */
class m200107_105720_sex extends Migration
{
    const TABLE = '{{%sex}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'sex_id' => $this->primaryKey(1),
            'sex_name' => $this->string(10),
        ]);

        $this->batchInsert(self::TABLE, ['sex_name'], [
            ['мужской'],
            ['женский'],
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
