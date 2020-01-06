<?php

namespace common\models\executors;

use common\components\interfaces\ExecuteInterface;
use common\models\db\Attitude;
use common\models\db\FireReason;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
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
    private $fireReasonId;

    /**
     * @var Team $team
     */
    private $team;

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
        $userId = $this->team->team_user_id;
        $viceId = $this->team->team_vice_id;

        $this->team->team_auto = 0;
        $this->team->team_attitude_national = Attitude::NEUTRAL;
        $this->team->team_attitude_president = Attitude::NEUTRAL;
        $this->team->team_attitude_u19 = Attitude::NEUTRAL;
        $this->team->team_attitude_u21 = Attitude::NEUTRAL;
        $this->team->team_user_id = 0;
        $this->team->team_vice_id = 0;
        $this->team->save(true, [
            'team_auto',
            'team_attitude_national',
            'team_attitude_president',
            'team_attitude_u19',
            'team_attitude_u21',
            'team_user_id',
            'team_vice_id',
        ]);

        TransferApplication::deleteAll([
            'transfer_application_team_id' => $this->team->team_id,
            'transfer_application_transfer_id' => Transfer::find()
                ->select(['transfer_id'])
                ->where(['transfer_ready' => 0])
        ]);

        TransferApplication::deleteAll([
            'transfer_application_transfer_id' => Transfer::find()
                ->select(['transfer_id'])
                ->where(['transfer_ready' => 0, 'transfer_team_seller_id' => $this->team->team_id])
        ]);

        $transferArray = Transfer::find()
            ->where(['transfer_team_seller_id' => $this->team->team_id, 'transfer_ready' => 0])
            ->all();
        foreach ($transferArray as $transfer) {
            $transfer->delete();
        }

        LoanApplication::deleteAll([
            'loan_application_team_id' => $this->team->team_id,
            'loan_application_loan_id' => Loan::find()
                ->select(['loan_id'])
                ->where(['loan_ready' => 0])
        ]);

        LoanApplication::deleteAll([
            'loan_application_loan_id' => Loan::find()
                ->select(['loan_id'])
                ->where(['loan_ready' => 0, 'loan_team_seller_id' => $this->team->team_id])
        ]);

        $loanArray = Loan::find()
            ->where(['loan_team_seller_id' => $this->team->team_id, 'loan_ready' => 0])
            ->all();
        foreach ($loanArray as $loan) {
            $loan->delete();
        }

        if ($userId) {
            History::log([
                'history_fire_reason' => $this->fireReasonId,
                'history_history_text_id' => HistoryText::USER_MANAGER_TEAM_OUT,
                'history_team_id' => $this->team->team_id,
                'history_user_id' => $userId,
            ]);
        }

        if ($viceId) {
            History::log([
                'history_history_text_id' => HistoryText::USER_VICE_TEAM_OUT,
                'history_team_id' => $this->team->team_id,
                'history_user_id' => $viceId,
            ]);
        }

        return true;
    }
}