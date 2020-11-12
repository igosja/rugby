<?php

// TODO refactor

namespace frontend\models\forms;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\helpers\ErrorHelper;
use common\models\db\User;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class ForgotPasswordForm
 * @package frontend\models\forms
 *
 * @property string $email
 * @property string $login
 */
class ForgotPasswordForm extends Model
{
    /**
     * @var string|null
     */
    public ?string $email = null;

    /**
     * @var string|null
     */
    public ?string $login = null;

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
            [['email'], AtLeastValidator::class, 'in' => ['email', 'login']],
            [['email', 'login'], 'trim'],
            [['email'], 'email'],
            [['login'], 'string'],
            [['email'], 'exist', 'targetClass' => User::class],
            [['login'], 'exist', 'targetClass' => User::class],
        ];
    }

    /**
     * @return bool
     */
    public function send(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        try {
            /**
             * @var User $user
             */
            $user = $this->getUser();

            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'password-html', 'text' => 'password-text'],
                    ['model' => $user]
                )
                ->setTo($user->email)
                ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['noReplyName']])
                ->setSubject('Восстановление пароля на сайте Виртуальной Регбийной Лиги')
                ->send();
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return false;
        }

        return true;
    }

    /**
     * @return User
     */
    private function getUser(): User
    {
        if (!$this->user) {
            $this->user = User::find()
                ->andFilterWhere(['email' => $this->email])
                ->andFilterWhere(['login' => $this->login])
                ->limit(1)
                ->one();
        }

        return $this->user;
    }
}
