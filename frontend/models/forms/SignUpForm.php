<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\User;
use common\models\db\UserLogin;
use common\models\db\UserRole;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class SignUpForm
 * @package frontend\models\forms
 *
 * @property string $email
 * @property string $login
 * @property string $password
 */
class SignUpForm extends Model
{
    /**
     * @var string $email
     */
    public $email = '';

    /**
     * @var string $login
     */
    public $login = '';

    /**
     * @var string $password
     */
    public $password = '';

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'login', 'password'], 'required'],
            [['email', 'login'], 'trim'],
            [['email'], 'email'],
            [['password'], 'string', 'min' => 6],
            [['email'], 'string', 'min' => 2, 'max' => 255],
            [['login'], 'string', 'min' => 2, 'max' => 30],
            [['login'], 'unique', 'targetClass' => User::class],
            [['email'], 'unique', 'targetClass' => User::class],
        ];
    }

    /**
     * @return bool
     */
    public function signUp(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $cookies = Yii::$app->request->cookies;
            $referrerUserId = $cookies->getValue('user_referrer_id', 0);

            $userIp = Yii::$app->request->headers->get('x-real-ip');
            if (!$userIp) {
                $userIp = Yii::$app->request->userIP;
            }

            $refUser = User::find()
                ->andWhere(['id' => $referrerUserId])
                ->andWhere([
                    'not',
                    [
                        'id' => UserLogin::find()
                            ->select(['user_id'])
                            ->andWhere([
                                'ip' => $userIp,
                                'user_id' => $referrerUserId,
                            ])
                    ]
                ])
                ->limit(1)
                ->one();
            if (!$refUser) {
                $referrerUserId = null;
            }

            $model = new User();
            $model->code = $this->generateUserCode();
            $model->date_register = time();
            $model->email = $this->email;
            $model->login = $this->login;
            $model->referrer_user_id = $referrerUserId;
            $model->password = Yii::$app->security->generatePasswordHash($this->password);
            $model->user_role_id = UserRole::USER;
            $model->save();

            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'signUp-html', 'text' => 'signUp-text'],
                    ['model' => $model]
                )
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['noReplyName']])
                ->setSubject(Yii::t('frontend', 'models.forms.sign-up.sign-up.subject'))
                ->send();

            if ($transaction) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    private function generateUserCode(): string
    {
        $code = md5(uniqid(mt_rand(), 1));
        if (!User::find()->where(['code' => $code])->exists()) {
            return $code;
        }
        return $this->generateUserCode();
    }
}
