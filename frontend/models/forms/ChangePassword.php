<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class ChangePassword
 * @package frontend\models
 *
 * @property $new string
 * @property $old string
 * @property $repeat string
 */
class ChangePassword extends Model
{
    public $new;
    public $old;
    public $repeat;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['new', 'old', 'repeat'], 'required'],
            [['old'], 'checkOldPassword'],
            [['repeat'], 'compare', 'compareAttribute' => 'new', 'message' => Yii::t('frontend', 'models.forms.change-password.rules.repeat.message')],
            [['new'], 'string', 'min' => 6],
        ];
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function change(): bool
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }
        if (!$this->validate()) {
            return false;
        }
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        $user->password = Yii::$app->security->generatePasswordHash($this->new);
        if (!$user->save(true, ['password'])) {
            return false;
        }
        return true;
    }

    /**
     * @param string $attribute
     */
    public function checkOldPassword(string $attribute): void
    {
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        if (!$user->validatePassword($this->old)) {
            $this->addError($attribute, Yii::t('frontend', 'models.forms.change-password.old-password.error'));
        }
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'new' => Yii::t('frontend', 'models.forms.change-password.label.new'),
            'old' => Yii::t('frontend', 'models.forms.change-password.label.old'),
            'repeat' => Yii::t('frontend', 'models.forms.change-password.label.repeat'),
        ];
    }
}
