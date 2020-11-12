<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000095HistoryText
 * @package console\migrations
 */
class M200101000095HistoryText extends Migration
{
    private const TABLE = '{{%history_text}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                ['Команда {team} зарегистрирована в Лиге'],
                ['Команда {team} перерегистрирована'],
                ['{user} принят на работу тренером-менеджером в команду {team}'],
                ['{user} покинул пост тренера-менеджера команды {team}'],
                ['{user} принят на работу заместителем менеджера в команду {team}'],
                ['{user} покинул пост заместителя менеджера команды {team}'],
                ['{user} принят на работу тренером-менеджером в сборную {national}'],
                ['{user} покинул пост тренера-менеджера сборной {national}'],
                ['{user} принят на работу заместителем менеджера в сборную {national}'],
                ['{user} покинул пост заместителя менеджера сборной {national}'],
                ['{user} избран президентом федерации {federation}'],
                ['{user} покинул пост президента федерации {federation}'],
                ['{user} избран заместителем президента федерации {federation}'],
                ['{user} покинул пост заместителя президента федерации {federation}'],
                ['Уровень строения {building} увеличен до {level} уровня'],
                ['Уровень строения {building} уменьшен до {level} уровня'],
                ['Стадион расширен до {capacity} мест'],
                ['Стадион уменьшен до {capacity} мест'],
                ['Произведен обмен любимых стилей игроков'],
                ['Произведен обмен спецвозможностей в команде'],
                ['За 1 место в регулярном чемпионате страны VIP-клуб сроком 3 мес.'],
                ['За 2 место в регулярном чемпионате страны VIP-клуб сроком 2 мес.'],
                ['За 3 место в регулярном чемпионате страны VIP-клуб сроком 1 мес.'],
                ['{player} пришел из спортшколы в команду {team}'],
                ['Объявил об уходе на пенсию'],
                ['Вышел на пенсию'],
                ['Натренировал балл силы на базе'],
                ['Натренировал совмещение {position} на базе'],
                ['Натренировал спецвозможность {special} на базе'],
                ['Получил балл силы по результатам матча {game}'],
                ['Потерял балл силы по результатам матча {game}'],
                ['Получил спецвозможность {special} по результатам чемпионата'],
                ['Натренировал бонусный балл силы на базе'],
                ['Натренировал бонусное совмещение {position} на базе'],
                ['Натренировал бонусную спецвозможность {special} на базе'],
                ['Получил травму на {day} в матче {game}'],
                ['Перешел из команды {team} в команду {team2}'],
                ['Перешел из команды {team} в команду {team2} в результате обмена'],
                ['Перешел из команды {team} в команду {team2} на правах аренды'],
                ['Вернулся в команду {team} из команды {team2} по окончании аренды'],
                ['Продан Лиге командой {team}'],
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
