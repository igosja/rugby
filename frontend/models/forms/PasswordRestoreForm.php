<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\User;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class PasswordRestore
 * @package frontend\models
 *
 * @property string $code
 * @property string $password
 */
class PasswordRestoreForm extends Model
{
    /**
     * @var string $code
     */
    public string $code = '';

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
            [['code', 'password'], 'required'],
            [['code'], 'trim'],
            [['password'], 'string', 'min' => 6],
            [['code'], 'exist', 'targetClass' => User::class],
        ];
    }

    /**
     * @return bool
     */
    public function restore(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        try {
            $user = $this->getUser();
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->save(true, ['password']);
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
                ->andWhere(['code' => $this->code])
                ->limit(1)
                ->one();
        }

        return $this->user;
    }
}
