<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\User;
use common\models\db\UserLogin;
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
    public $login = '';

    /**
     * @var string $password
     */
    public $password = '';

    /**
     * @var User|null
     */
    private $user;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['login', 'password'], 'required'],
            [['login'], 'trim'],
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
                $this->addError($attribute, Yii::t('frontend', 'models.forms.sign-in.password.error'));
            }
        }
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        if ($this->validate()) {
            if (!Yii::$app->user->login($this->getUser(), 3600 * 24 * 30)) {
                return false;
            }
            return $this->updateLastUserLogin();
        }

        return false;
    }

    private function updateLastUserLogin(): bool
    {
        $userId = Yii::$app->user->id;
        $userIp = Yii::$app->request->headers->get('x-real-ip');
        if (!$userIp) {
            $userIp = Yii::$app->request->userIP;
        }
        $userAgent = Yii::$app->request->userAgent;
        $userLogin = UserLogin::find()
            ->andWhere(
                [
                    'user_id' => $userId,
                    'ip' => $userIp,
                    'agent' => $userAgent
                ]
            )
            ->limit(1)
            ->one();
        if (!$userLogin) {
            $userLogin = new UserLogin();
            $userLogin->user_id = $userId;
            $userLogin->ip = $userIp;
            $userLogin->agent = $userAgent;
        }
        return $userLogin->save();
    }

    /**
     * @return User|null
     */
    private function getUser(): ?User
    {
        if (!$this->user) {
            $this->user = User::find()
                ->andWhere(['login' => $this->login])
                ->limit(1)
                ->one();
        }

        return $this->user;
    }
}
