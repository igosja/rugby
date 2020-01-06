<?php

use yii\db\Migration;

/**
 * Class m200107_111701_tournament_type
 */
class m200107_111701_tournament_type extends Migration
{
    const TABLE = '{{%tournament_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'tournament_type_id' => $this->primaryKey(1),
            'tournament_type_day_type_id' => $this->integer(1)->defaultValue(0),
            'tournament_type_name' => $this->string(20),
            'tournament_type_visitor' => $this->integer(3)->defaultValue(0),
        ]);

        $this->batchInsert(
            self::TABLE,
            ['tournament_type_day_type_id', 'tournament_type_name', 'tournament_type_visitor'],
            [
                [3, 'Чемпионат мира', 200],
                [3, 'Лига Чемпионов', 150],
                [2, 'Чемпионат', 100],
                [2, 'Конференция', 90],
                [2, 'Кубок межсезонья', 90],
                [1, 'Товарищеский матч', 80],
            ]
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
