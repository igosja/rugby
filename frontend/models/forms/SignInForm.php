<?php

namespace frontend\models\forms;

use common\models\db\User;
use Yii;
use yii\base\Model;

/**
 * Class SignInForm
 * @package frontend\models\forms
 *
 * @property string $login
 * @property string $password
 */
class SignInForm extends Model
{
    /**
     * @var string $login
     */
    public $login;

    /**
     * @var string $password
     */
    public $password;

    /**
     * @var User $_user
     */
    private $_user;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['login', 'password'], 'required'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильная комбинация логин/пароль');
            }
        }
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
        }

        return false;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    /**
     * @return User|null
     */
    private function getUser(): ?User
    {
        if (!$this->_user) {
            $this->_user = User::find()
                ->where(['user_login' => $this->login])
                ->limit(1)
                ->one();
        }

        return $this->_user;
    }
}
