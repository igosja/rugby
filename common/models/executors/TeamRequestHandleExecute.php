<?php

namespace common\models\executors;

use common\components\interfaces\ExecuteInterface;
use common\models\db\Team;
use common\models\db\TeamRequest;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class TeamRequestExecutor
 * @package console\models\executors
 *
 * @property-read TeamRequest $teamRequest
 */
class TeamRequestHandleExecute implements ExecuteInterface
{
    /**
     * @var TeamRequest $teamRequest
     */
    private $teamRequest;

    /**
     * TeamRequestHandleExecute constructor.
     * @param TeamRequest $teamRequest
     */
    public function __construct(TeamRequest $teamRequest)
    {
        $this->teamRequest = $teamRequest;
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function execute(): bool
    {
        $teamToEmploy = Team::find()
            ->where(['team_id' => $this->teamRequest->team_request_team_id, 'team_user_id' => 0])
            ->limit(1)
            ->one();
        if (!$teamToEmploy) {
            TeamRequest::deleteAll(['team_request_id' => $this->teamRequest->team_request_id]);
            return false;
        }

        if ($this->teamRequest->team_request_leave_id) {
            $teamToFire = Team::find()
                ->where(['team_id' => $this->teamRequest->team_request_leave_id])
                ->limit(1)
                ->one();
            if ($teamToFire) {
                (new TeamManagerFireExecute($teamToFire))->execute();
            }
        }

        (new TeamManagerEmployExecute($teamToEmploy, $this->teamRequest->user))->execute();

        TeamRequest::deleteAll(['team_request_team_id' => $this->teamRequest->team_request_team_id]);
        TeamRequest::deleteAll(['team_request_user_id' => $this->teamRequest->team_request_user_id]);

        return true;
    }
}