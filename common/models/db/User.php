<?php

namespace common\models\db;

use common\components\helpers\FormatHelper;
use frontend\components\AbstractController;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\models\db
 *
 * @property int $user_id
 * @property int $user_birth_day
 * @property int $user_birth_month
 * @property int $user_birth_year
 * @property string $user_city
 * @property string $user_code
 * @property int $user_country_id
 * @property int $user_date_confirm
 * @property int $user_date_delete
 * @property int $user_date_login
 * @property int $user_date_register
 * @property int $user_date_vip
 * @property string $user_email
 * @property int $user_finance
 * @property int $user_language_id
 * @property string $user_login
 * @property float $user_money
 * @property string $user_name
 * @property int $user_news_id
 * @property int $user_no_vice
 * @property string $user_notes
 * @property int $user_password
 * @property float $user_rating
 * @property int $user_referrer_done
 * @property int $user_referrer_id
 * @property int $user_sex_id
 * @property string $user_social_facebook_id
 * @property string $user_social_google_id
 * @property string $user_surname
 * @property string $user_timezone
 * @property int $user_user_role_id
 *
 * @property UserBlock[] $userBlocks
 * @property UserBlock $userBlockCommentActive
 * @property UserBlock $userBlockCommentNewsActive
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ADMIN_USER_ID = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->generateUserCode();
            $this->user_date_register = time();
        }
        return true;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public static function findIdentity($id)
    {
        return self::find()
            ->where(['user_id' => $id])
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
            ->where(['user_code' => $token])
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
        return $this->user_code;
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
        return Yii::$app->security->validatePassword($password, $this->user_password);
    }

    /**
     * @return string
     */
    public function lastVisit(): string
    {
        $date = $this->user_date_login;
        $min_5 = $date + 5 * 60;
        $min_60 = $date + 60 * 60;
        $now = time();

        if ($min_5 >= $now) {
            $date = '<span class="red">online</span>';
        } elseif ($min_60 >= $now) {
            $difference = $now - $date;
            $difference = $difference / 60;
            $difference = round($difference, 0);
            $date = $difference . ' минут назад';
        } elseif (0 == $date) {
            $date = '-';
        } else {
            $date = FormatHelper::asDateTime($date);
        }

        return $date;
    }

    /**
     * @return bool
     */
    public function generateUserCode(): bool
    {
        $code = md5(uniqid(rand(), 1));
        if (!self::find()->where(['user_code' => $code])->exists()) {
            $this->user_code = $code;
        } else {
            $this->generateUserCode();
        }
        return true;
    }

    /**
     * @return string
     */
    public function iconVip(): string
    {
        $result = '';
        if ($this->isVip()) {
            $result = ' <i aria-hidden="true" class="fa fa-star font-yellow" title="VIP"></i>';
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function canDialog(): bool
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        if (!$controller->user) {
            return false;
        }
        if (!$this->user_id) {
            return false;
        }
        if ($this->user_id == Yii::$app->user->id) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        $result = 'Новый менеджер';
        if ($this->user_name || $this->user_surname) {
            $result = $this->user_name . ' ' . $this->user_surname;
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isVip(): bool
    {
        return $this->user_date_vip > time();
    }

    /**
     * @param array $options
     * @return string
     */
    public function userLink(array $options = []): string
    {
        if (isset($options['color']) && UserRole::ADMIN == $this->user_user_role_id) {
            unset($options['color']);
            $options = ArrayHelper::merge($options, ['class' => 'red']);
        }

        return Html::a(
            Html::encode($this->user_login),
            ['user/view', 'id' => $this->user_id],
            $options
        );
    }

    /**
     * @return UserBlock|null
     */
    public function getCommentNewsBlock()
    {
        if ($this->userBlockCommentNewsActive) {
            return $this->userBlockCommentNewsActive;
        }
        if ($this->userBlockCommentActive) {
            return $this->userBlockCommentActive;
        }
        return null;
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlock(): ActiveQuery
    {
        return $this
            ->hasOne(UserBlock::class, ['user_block_user_id' => 'user_id'])
            ->select([
                'user_block_date',
                'user_block_user_block_reason_id',
                'user_block_user_id',
            ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlockComment(): ActiveQuery
    {
        return $this
            ->getUserBlock()
            ->andWhere(['user_block_user_block_type_id' => UserBlockType::TYPE_COMMENT]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlockCommentNews(): ActiveQuery
    {
        return $this
            ->getUserBlock()
            ->andWhere(['user_block_user_block_type_id' => UserBlockType::TYPE_COMMENT_NEWS]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlockCommentActive(): ActiveQuery
    {
        return $this
            ->getUserBlockComment()
            ->andWhere(['>', 'user_block_date', time()]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlockCommentNewsActive(): ActiveQuery
    {
        return $this
            ->getUserBlockCommentNews()
            ->andWhere(['>', 'user_block_date', time()]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlocks(): ActiveQuery
    {
        return $this->hasMany(UserBlock::class, ['user_block_user_id' => 'user_id']);
    }
}
