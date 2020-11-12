<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\User;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class ActivationRepeatForm
 * @package frontend\models\forms
 *
 * @property string $email
 */
class ActivationRepeatForm extends Model
{
    /**
     * @var string $email
     */
    public string $email = '';

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
            [['email'], 'required'],
            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'exist', 'targetClass' => User::class],
            [['email'], 'checkEmail'],
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
            $user = $this->getUser();

            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'signUp-html', 'text' => 'signUp-text'],
                    ['model' => $user]
                )
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['noReplyName']])
                ->setSubject('Регистрация на сайте Виртуальной Хоккейной Лиги')
                ->send();
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function checkEmail(string $attribute): bool
    {
        $user = $this->getUser();

        if ($user->date_confirm) {
            $this->addError($attribute, 'Профиль уже активирован');
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
                ->andWhere(['email' => $this->email])
                ->limit(1)
                ->one();
        }

        return $this->user;
    }
}
