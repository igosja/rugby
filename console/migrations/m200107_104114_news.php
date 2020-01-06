<?php

use yii\db\Migration;

/**
 * Class m200107_104114_news
 */
class m200107_104114_news extends Migration
{
    const TABLE = '{{%news}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'news_id' => $this->primaryKey(11),
            'news_check' => $this->integer(11)->defaultValue(0),
            'news_country_id' => $this->integer(3)->defaultValue(0),
            'news_date' => $this->integer(11)->defaultValue(0),
            'news_text' => $this->text(),
            'news_title' => $this->string(255),
            'news_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('news_country_id', self::TABLE, 'news_country_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
