<?php

// TODO refactor

namespace backend\models\forms;

use common\models\db\User;
use common\models\db\UserRole;
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
    public string $login = '';

    /**
     * @var string $password
     */
    public string $password = '';

    /**
     * @var User|null $user
     */
    private ?User $user = null;

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
     * @return bool
     */
    public function validatePassword(string $attribute): bool
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('backend', 'models.forms.sing-in.password.error'));
            }
        }

        return true;
    }

    /**
     * @return User|null
     */
    private function getUser(): ?User
    {
        if (null === $this->user) {
            $this->user = User::find()
                ->where(['login' => $this->login, 'user_role_id' => UserRole::ADMIN])
                ->limit(1)
                ->one();
        }

        return $this->user;
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
}
