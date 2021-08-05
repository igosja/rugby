<?php

namespace frontend\models\forms;

use common\models\db\Federation;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Team;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class FederationTransferFinance
 * @package frontend\models\forms
 *
 * @property string $comment
 * @property Federation $federation
 * @property int $sum
 * @property int $teamId
 */
class FederationTransferFinance extends Model
{
    public $comment;
    public $federation;
    public $sum;
    public $teamId;

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
            [['teamId'], 'integer', 'min' => 1],
            [['teamId'], 'required'],
            [['sum'], 'integer', 'min' => 1, 'max' => $this->federation->finance],
            [['sum'], 'required'],
            [['comment'], 'safe'],
            [['teamId'], 'exist', 'targetClass' => Team::class, 'targetAttribute' => ['teamId' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'comment' => Yii::t('frontend', 'models.forms.federation-transfer-finance.label.comment'),
            'sum' => Yii::t('frontend', 'models.forms.federation-transfer-finance.label.sum'),
            'teamId' => Yii::t('frontend', 'models.forms.federation-transfer-finance.label.team'),
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
        $this->outcomeFederation();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function incomeTeam(): bool
    {
        $team = Team::find()
            ->where(['id' => $this->teamId])
            ->limit(1)
            ->one();
        if (!$team) {
            return false;
        }

        Finance::log([
            'comment' => ($this->comment ? $this->comment . ' ' : '') . $this->federation->country->name,
            'finance_text_id' => FinanceText::FEDERATION_TRANSFER,
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
    private function outcomeFederation(): void
    {
        $team = Team::find()
            ->where(['id' => $this->teamId])
            ->limit(1)
            ->one();

        Finance::log([
            'comment' => ($this->comment ? $this->comment . ' ' : '') . $team->name,
            'federation_id' => $this->federation->id,
            'finance_text_id' => FinanceText::FEDERATION_TRANSFER,
            'value' => $this->sum,
            'value_after' => $this->federation->finance - $this->sum,
            'value_before' => $this->federation->finance,
        ]);

        $this->federation->finance -= $this->sum;
        $this->federation->save(true, ['finance']);
    }
}
