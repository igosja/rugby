<?php

namespace frontend\models\forms;

use common\models\db\Money;
use common\models\db\MoneyText;
use common\models\db\User;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class UserTransferMoney
 * @package frontend\models
 *
 * @property int $sum
 * @property User $user
 * @property int $userId
 */
class UserTransferMoney extends Model
{
    public $sum;
    public $user;
    public $userId;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['userId'], 'integer', 'min' => 1],
            [['sum'], 'number', 'min' => 0.01, 'max' => $this->user->money],
            [['sum', 'userId'], 'required'],
            [['userId'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'sum' => Yii::t('frontend', 'models.forms.user-transfer-money.label.sum'),
            'userId' => Yii::t('frontend', 'models.forms.user-transfer-money.label.user'),
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute()
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $this->incomeUser();
        $this->outcomeUser();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function incomeUser(): bool
    {
        $user = User::find()
            ->where(['id' => $this->userId])
            ->limit(1)
            ->one();
        if (!$user) {
            return false;
        }

        Money::log([
            'money_text_id' => MoneyText::INCOME_FRIEND,
            'user_id' => $user->id,
            'value' => $this->sum,
            'value_after' => $user->money + $this->sum,
            'value_before' => $user->money,
        ]);

        $user->money += $this->sum;
        $user->save(true, ['money']);

        return true;
    }

    /**
     * @throws Exception
     */
    private function outcomeUser(): void
    {
        Money::log([
            'money_text_id' => MoneyText::OUTCOME_FRIEND,
            'user_id' => $this->user->id,
            'value' => -$this->sum,
            'value_after' => $this->user->money - $this->sum,
            'value_before' => $this->user->money,
        ]);

        $this->user->money -= $this->sum;
        $this->user->save(true, ['money']);
    }
}
