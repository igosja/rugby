<?php

namespace frontend\models\forms;

use common\models\db\User;
use yii\base\Model;

/**
 * Class ActivationForm
 * @package frontend\models\forms
 *
 * @property string $code
 */
class ActivationForm extends Model
{
    /**
     * @var string $code
     */
    public string $code = '';

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
            [['code'], 'required'],
            [['code'], 'trim'],
            [['code'], 'string', 'length' => 32],
            [['code'], 'exist', 'targetClass' => User::class],
            [['code'], 'checkCode'],
        ];
    }

    /**
     * @return bool
     */
    public function activate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $user->date_confirm = time();
        return $user->save(true, ['date_confirm']);
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function checkCode(string $attribute): bool
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
                ->andWhere(['code' => $this->code])
                ->limit(1)
                ->one();
        }

        return $this->user;
    }
}
