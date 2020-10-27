<?php

namespace console\migrations;

use Yii;
use yii\db\Exception;
use yii\db\Migration;

/**
 * Class M200101000019User
 * @package console\migrations
 */
class M200101000019User extends Migration
{
    private const TABLE = '{{%user}}';

    /**
     * @return bool
     * @throws Exception
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'birth_day' => $this->integer(2)->defaultValue(0),
                'birth_month' => $this->integer(2)->defaultValue(0),
                'birth_year' => $this->integer(4)->defaultValue(0),
                'city' => $this->string(255),
                'code' => $this->char(32)->unique(),
                'country_id' => $this->integer(3),
                'date_confirm' => $this->integer(11),
                'date_delete' => $this->integer(11),
                'date_login' => $this->integer(11),
                'date_register' => $this->integer(11),
                'date_vip' => $this->integer(11),
                'email' => $this->string(255)->unique(),
                'finance' => $this->integer(11)->defaultValue(0),
                'is_no_vice' => $this->boolean()->defaultValue(false),
                'is_referrer_done' => $this->boolean()->defaultValue(false),
                'language_id' => $this->integer(3)->defaultValue(1),
                'login' => $this->string(255)->unique(),
                'money' => $this->decimal(11, 2)->defaultValue(0),
                'name' => $this->string(255),
                'news_id' => $this->integer(11),
                'notes' => $this->text(),
                'password' => $this->string(255),
                'rating' => $this->decimal(6, 2)->defaultValue(500),
                'referrer_user_id' => $this->integer(11),
                'sex_id' => $this->integer(1),
                'social_facebook_id' => $this->string(255)->unique(),
                'social_google_id' => $this->string(255)->unique(),
                'surname' => $this->string(255),
                'timezone' => $this->string(255)->defaultValue('UTC'),
                'user_role_id' => $this->integer(1)->defaultValue(1),
            ]
        );

        $this->addForeignKey('user_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey('user_language_id', self::TABLE, 'language_id', '{{%language}}', 'id');
        $this->addForeignKey('user_referrer_id', self::TABLE, 'referrer_id', self::TABLE, 'id');
        $this->addForeignKey('user_sex_id', self::TABLE, 'sex_id', '{{%sex}}', 'id');
        $this->addForeignKey('user_user_role_id', self::TABLE, 'user_role_id', '{{%user_role}}', 'id');

        $this->insert(
            self::TABLE,
            [
                'code' => '00000000000000000000000000000000',
                'date_confirm' => time(),
                'date_register' => time(),
                'email' => 'info@virtual-rugby.com',
                'login' => 'Free team',
                'name' => 'Free',
                'password' => '0',
                'surname' => 'team',
                'user_role_id' => 1
            ]
        );

        $this->update(self::TABLE, ['id' => 0], ['id' => 1]);

        Yii::$app->db->createCommand('ALTER TABLE ' . self::TABLE . ' AUTO_INCREMENT=1')->execute();

        $this->insert(
            self::TABLE,
            [
                'code' => '13373e3c14aa77368437c7c972601d70',
                'date_confirm' => time(),
                'date_register' => time(),
                'email' => 'igosja@ukr.net',
                'login' => 'Igosja',
                'password' => '$2y$13$KI4fvLJvQ.OGMXEtnj0T.ugY.1ssWb1QV/3ucbKqegh/gRQxnbMRO',
                'user_role_id' => 5,
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
