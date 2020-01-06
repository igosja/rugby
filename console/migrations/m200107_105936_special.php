<?php

use yii\db\Migration;

/**
 * Class m200107_105936_special
 */
class m200107_105936_special extends Migration
{
    const TABLE = '{{%special}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'special_id' => $this->primaryKey(2),
            'special_name' => $this->string(2),
            'special_text' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['special_name', 'special_text'], [
            ['Ск', 'Скорость'],
            ['Сб', 'Силовая борьба'],
            ['Т', 'Техника'],
            ['Л', 'Лидер'],
            ['Ат', 'Атлетизм'],
            ['Р', 'Реакция'],
            ['От', 'Отбор'],
            ['Бр', 'Бросок'],
            ['К', 'Кумир'],
            ['Кл', 'Игра клюшкой'],
            ['П', 'Выбор позиции'],
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
