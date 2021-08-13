<?php

// TODO refactor

namespace common\models\executors;

use common\components\interfaces\ExecuteInterface;
use common\models\db\Attitude;
use common\models\db\FireReason;
use common\models\db\HistoryText;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
use frontend\models\executors\HistoryLogExecutor;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class TeamManagerFireExecute
 * @package console\models\executors
 *
 * @property-read int $fireReasonId
 * @property-read Team $team
 */
class TeamManagerFireExecute implements ExecuteInterface
{
    /**
     * @var int $fireReasonId
     */
    private int $fireReasonId;

    /**
     * @var Team $team
     */
    private Team $team;

    /**
     * TeamManagerFireExecute constructor.
     * @param Team $team
     * @param int $fireReasonId
     */
    public function __construct(Team $team, int $fireReasonId = FireReason::FIRE_REASON_SELF)
    {
        $this->fireReasonId = $fireReasonId;
        $this->team = $team;
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function execute(): bool
    {
        $userId = $this->team->user_id;
        $viceId = $this->team->vice_user_id;

        $this->team->auto_number = 0;
        $this->team->national_attitude_id = Attitude::NEUTRAL;
        $this->team->president_attitude_id = Attitude::NEUTRAL;
        $this->team->u19_attitude_id = Attitude::NEUTRAL;
        $this->team->u21_attitude_id = Attitude::NEUTRAL;
        $this->team->user_id = 0;
        $this->team->vice_user_id = 0;
        $this->team->save(true, [
            'auto_number',
            'national_attitude_id',
            'president_attitude_id',
            'u19_attitude_id',
            'u21_attitude_id',
            'user_id',
            'vice_user_id',
        ]);

        TransferApplication::deleteAll([
            'team_id' => $this->team->id,
            'transfer_id' => Transfer::find()
                ->select(['id'])
                ->andWhere(['ready' => null])
        ]);

        TransferApplication::deleteAll([
            'transfer_id' => Transfer::find()
                ->select(['id'])
                ->andWhere(['ready' => null, 'team_seller_id' => $this->team->id])
        ]);

        $transferArray = Transfer::find()
            ->andWhere(['team_seller_id' => $this->team->id, 'ready' => null])
            ->all();
        foreach ($transferArray as $transfer) {
            $transfer->delete();
        }

        LoanApplication::deleteAll([
            'team_id' => $this->team->id,
            'loan_id' => Loan::find()
                ->select(['id'])
                ->andWhere(['ready' => null])
        ]);

        LoanApplication::deleteAll([
            'loan_id' => Loan::find()
                ->select(['id'])
                ->andWhere(['ready' => null, 'team_seller_id' => $this->team->id])
        ]);

        $loanArray = Loan::find()
            ->andWhere(['team_seller_id' => $this->team->id, 'ready' => null])
            ->all();
        foreach ($loanArray as $loan) {
            $loan->delete();
        }

        if ($userId) {
            (new HistoryLogExecutor(
                [
                    'fire_reason_id' => $this->fireReasonId,
                    'history_text_id' => HistoryText::USER_MANAGER_TEAM_OUT,
                    'team_id' => $this->team->id,
                    'user_id' => $userId,
                ]
            ))->execute();
        }

        if ($viceId) {
            (new HistoryLogExecutor(
                [
                    'history_text_id' => HistoryText::USER_VICE_TEAM_OUT,
                    'team_id' => $this->team->id,
                    'user_id' => $viceId,
                ]
            ))->execute();
        }

        return true;
    }
}