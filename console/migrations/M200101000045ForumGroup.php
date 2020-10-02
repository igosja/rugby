<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000045ForumGroup
 * @package console\migrations
 */
class M200101000045ForumGroup extends Migration
{
    private const TABLE = '{{%forum_group}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'country_id' => $this->integer(3),
                'description' => $this->text()->notNull(),
                'forum_chapter_id' => $this->integer(11)->notNull(),
                'name' => $this->string(255)->notNull(),
                'order' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('forum_group_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey(
            'forum_group_forum_chapter_id',
            self::TABLE,
            'forum_chapter_id',
            '{{%forum_chapter}}',
            'id'
        );

        $this->createIndex('forum_group_order', self::TABLE, ['forum_chapter_id', 'order'], true);

        $this->batchInsert(
            self::TABLE,
            [
                'country_id',
                'name',
                'description',
                'forum_chapter_id',
                'order'
            ],
            [
                [
                    0,
                    'О Лиге',
                    'вопросы и комментарии о лиге глобального характера, творчество, что нравится/не нравится',
                    1,
                    1
                ],
                [
                    0,
                    'Скорая помощь',
                    'сверxсрочные проблемы, не терпящие отлагательств, а то будет поздно - остальное в баги или вопросы новичков',
                    1,
                    2
                ],
                [
                    0,
                    'Вопросы новичков',
                    'для обсуждения с опытными менеджерами возникающих у новичков самых простых вопросов',
                    1,
                    3
                ],
                [
                    0,
                    'Регистрация и команды',
                    '[пере]регистрация, выставление/раздача свободных команд, переходы из клуба в клуб, верните команду и т.д.',
                    1,
                    4
                ],
                [
                    0,
                    'Идеи и предложения',
                    'высказывание интересных мыслей и конкретных идей по поводу развития лиги',
                    1,
                    5
                ],
                [0, 'Правила', 'обсуждение, трактовка, серьезные вопросы по поводу действующих правил', 1, 6],
                [0, 'Трансферы', 'если хотите договориться о купле/продаже игроков', 2, 1],
                [0, 'Товарищеские Матчи', 'поиск соперников на товы', 2, 2],
                [0, 'Аренда', 'если хотите договориться об аренде игроков', 2, 3],
                [0, 'Встречи', 'встречи менеджеров в реале - на стадион сходить, пивка попить', 3, 1],
                [0, 'Реальное регби', 'обсуждение реальных событий, регбийный оффтопик', 3, 2],
                [0, 'Оффтопик', 'обсуждение всего чего угодно за пределами Лиги', 3, 3],
                [8, 'Argentina', 'национальный форум', 4, 1],
                [10, 'Australia', 'национальный форум', 4, 2],
                [55, 'England', 'национальный форум', 4, 3],
                [62, 'France', 'национальный форум', 4, 4],
                [82, 'Ireland', 'национальный форум', 4, 5],
                [84, 'Italy', 'национальный форум', 4, 6],
                [125, 'New Zealand', 'национальный форум', 4, 7],
                [155, 'Scotland', 'национальный форум', 4, 8],
                [165, 'South Africa', 'национальный форум', 4, 9],
                [195, 'Wales', 'национальный форум', 4, 10],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
