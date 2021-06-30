<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\LoanApplication;
use common\models\db\Player;
use common\models\db\Team;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class LoanApplicationFrom
 * @package frontend\models
 *
 * @property bool $off
 * @property Player $player
 * @property Team $team
 */
class LoanApplicationFrom extends Model
{
    public $off;
    public $player;
    public $team;

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

        if (!$this->player->loan) {
            return false;
        }

        $loanApplication = LoanApplication::find()
            ->where([
                'loan_id' => $this->player->loan->id,
                'team_id' => $this->team->id,
            ])
            ->limit(1)
            ->one();
        if (!$loanApplication) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-from.error'));
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $loanApplication->delete();
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'models.forms.loan-application-from.success'));
            if ($transaction) {
                $transaction->commit();
            }
        } catch (Throwable $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
