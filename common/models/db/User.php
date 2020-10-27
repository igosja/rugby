<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\models\db
 *
 * @property int $id
 * @property int $birth_day
 * @property int $birth_month
 * @property int $birth_year
 * @property string $city
 * @property string $code
 * @property int $country_id
 * @property int $date_confirm
 * @property int $date_delete
 * @property int $date_login
 * @property int $date_register
 * @property int $date_vip
 * @property string $email
 * @property int $finance
 * @property bool $is_no_vice
 * @property bool $is_referrer_done
 * @property int $language_id
 * @property string $login
 * @property float $money
 * @property string $name
 * @property int $news_id
 * @property string $notes
 * @property int $password
 * @property float $rating
 * @property int $referrer_user_id
 * @property int $sex_id
 * @property string $social_facebook_id
 * @property string $social_google_id
 * @property string $surname
 * @property string $timezone
 * @property int $user_role_id
 *
 * @property-read string $authKey
 * @property-read Country $country
 * @property-read Language $language
 * @property-read News $news
 * @property-read User $referrerUser
 * @property-read Sex $sex
 * @property-read UserRole $userRole
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const ADMIN_USER_ID = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['email', 'login', 'user_role_id'], 'required'],
            [['email', 'login', 'social_facebook_id', 'social_google_id'], 'unique'],
            [['email'], 'email'],
            [['is_no_vice', 'is_referrer_done'], 'boolean'],
            [
                [
                    'city',
                    'code',
                    'email',
                    'login',
                    'name',
                    'notes',
                    'password',
                    'social_facebook_id',
                    'social_google_id',
                    'surname',
                    'timezone'
                ],
                'trim'
            ],
            [['code'], 'string', 'length' => 32],
            [
                [
                    'city',
                    'email',
                    'login',
                    'name',
                    'password',
                    'social_facebook_id',
                    'social_google_id',
                    'surname',
                    'timezone'
                ],
                'string',
                'max' => 255
            ],
            [['notes'], 'string'],
            [['sex_id', 'user_role_id'], 'integer', 'min' => 1, 'max' => 9],
            [['birth_day'], 'integer', 'min' => 1, 'max' => 31],
            [['birth_month'], 'integer', 'min' => 1, 'max' => 12],
            [['country_id', 'language_id'], 'integer', 'min' => 1, 'max' => 999],
            [['birth_year'], 'integer', 'min' => 1, 'max' => date('Y')],
            [['rating'], 'number', 'min' => 1, 'max' => 9999],
            [['money'], 'number', 'min' => 1, 'max' => 999999999],
            [
                [
                    'date_confirm',
                    'date_delete',
                    'date_login',
                    'date_register',
                    'date_vip',
                    'finance',
                    'news_id',
                    'referrer_user_id',
                ],
                'number',
                'min' => 0
            ],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['language_id'], 'exist', 'targetRelation' => 'language'],
            [['news_id'], 'exist', 'targetRelation' => 'news'],
            [['referrer_user_id'], 'exist', 'targetRelation' => 'referrerUser'],
            [['sex_id'], 'exist', 'targetRelation' => 'sex'],
            [['user_role_id'], 'exist', 'targetRelation' => 'userRole'],
        ];
    }

    /**
     * @param int $id
     * @return User|null
     */
    public static function findIdentity($id)
    {
        return self::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return array|User|ActiveRecord|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->where(['code' => $token])
            ->limit(1)
            ->one();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->code;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLanguage(): ActiveQuery
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReferrerUser(): ActiveQuery
    {
        return $this->hasOne(self::class, ['id' => 'referrer_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSex(): ActiveQuery
    {
        return $this->hasOne(Sex::class, ['id' => 'sex_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserRole(): ActiveQuery
    {
        return $this->hasOne(UserRole::class, ['id' => 'user_role_id']);
    }
}
