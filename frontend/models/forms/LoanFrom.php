<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\Player;
use common\models\db\Team;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class LoanFrom
 * @package frontend\models
 *
 * @property bool $off
 * @property Player $player
 * @property Team $team
 * @property LoanApplication[] $loanApplicationArray
 */
class LoanFrom extends Model
{
    public $off;
    public $player;
    public $team;
    public $loanApplicationArray;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->loanApplicationArray = LoanApplication::find()
            ->where(['loan_id' => ($this->player->loan->id ?? 0)])
            ->orderBy(['price' => SORT_DESC, 'date' => SORT_ASC])
            ->all();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['off'], 'boolean'],
            [['off'], 'required'],
        ];
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $loan = Loan::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->one();
        if (!$loan) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $loan->delete();
            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', Yii::t('frontend', 'models.forms.loan-from.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
