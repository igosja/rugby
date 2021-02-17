<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Team;
use common\models\db\User;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class UserTransferFinance
 * @package frontend\models
 *
 * @property string $comment
 * @property int $federationId
 * @property int $sum
 * @property int $teamId
 * @property User $user
 */
class UserTransferFinance extends Model
{
    /**
     * @var string $comment
     */
    public $comment;

    /**
     * @var int $sum
     */
    public $sum;

    /**
     * @var int $teamId
     */
    public $teamId;

    /**
     * @var User $user
     */
    public $user;

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
            [['teamId'], 'required', 'min' => 1],
            [['teamId'], 'integer', 'min' => 1],
            [['sum'], 'integer', 'min' => 1, 'max' => $this->user->finance],
            [['sum'], 'required'],
            [['comment'], 'trim'],
            [['comment'], 'string'],
            [['teamId'], 'exist', 'targetClass' => Team::class, 'targetAttribute' => ['teamId' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'comment' => Yii::t('frontend', 'models.forms.user-transfer-finance.label.comment'),
            'sum' => Yii::t('frontend', 'models.forms.user-transfer-finance.label.sum'),
            'teamId' => Yii::t('frontend', 'models.forms.user-transfer-finance.label.team'),
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $this->incomeTeam();
        $this->outcomeUser();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function incomeTeam(): bool
    {
        /**
         * @var Team $team
         */
        $team = Team::find()
            ->where(['id' => $this->teamId])
            ->limit(1)
            ->one();
        if (!$team) {
            return false;
        }

        Finance::log([
            'comment' => ($this->comment ? $this->comment . ' ' : '') . Html::encode($this->user->login),
            'finance_text_id' => FinanceText::USER_TRANSFER,
            'team_id' => $team->id,
            'value' => $this->sum,
            'value_after' => $team->finance + $this->sum,
            'value_before' => $team->finance,
        ]);

        $team->finance += $this->sum;
        $team->save(true, ['finance']);

        return true;
    }

    /**
     * @throws Exception
     */
    private function outcomeUser(): void
    {
        Finance::log([
            'finance_text_id' => FinanceText::USER_TRANSFER,
            'user_id' => $this->user->id,
            'value' => $this->sum,
            'value_after' => $this->user->finance - $this->sum,
            'value_before' => $this->user->finance,
        ]);

        $this->user->finance -= $this->sum;
        $this->user->save(true, ['finance']);
    }
}
