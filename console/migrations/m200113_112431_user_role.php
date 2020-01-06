<?php

use yii\db\Migration;

/**
 * Class m200107_112431_user_role
 */
class m200113_112431_user_role extends Migration
{
    const TABLE = '{{%user_role}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_role_id' => $this->primaryKey(1),
            'user_role_name' => $this->string(20),
        ]);

        $this->batchInsert(self::TABLE, ['user_role_name'], [
            ['Пользователь'],
            ['Поддержка'],
            ['Редактор'],
            ['Модератор'],
            ['Администратор'],
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
