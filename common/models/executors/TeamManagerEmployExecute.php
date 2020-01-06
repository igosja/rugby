<?php

namespace common\models\executors;

use common\components\interfaces\ExecuteInterface;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Team;
use common\models\db\User;
use Throwable;
use Yii;
use yii\db\StaleObjectException;

/**
 * Class TeamManagerFireExecute
 * @package console\models\executors
 *
 * @property-read Team $team
 * @property-read User $user
 */
class TeamManagerEmployExecute implements ExecuteInterface
{
    /**
     * @var Team $team
     */
    private $team;

    /**
     * @var User $user
     */
    private $user;

    /**
     * TeamManagerEmployExecute constructor.
     * @param Team $team
     * @param User $user
     */
    public function __construct(Team $team, User $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function execute(): bool
    {
        $this->team->team_user_id = $this->user->user_id;
        $this->team->save();

        History::log([
            'history_history_text_id' => HistoryText::USER_MANAGER_TEAM_IN,
            'history_team_id' => $this->team->team_id,
            'history_user_id' => $this->user->user_id,
        ]);

        /**
         * @var Team[] $viceTeamArray
         */
        $viceTeamArray = Team::find()
            ->joinWith(['stadium.city.country'])
            ->where(['team_vice_id' => $this->user->user_id, 'country_id' => $this->team->stadium->city->country->country_id])
            ->orderBy(['team_id' => SORT_ASC])
            ->all();
        foreach ($viceTeamArray as $viceTeam) {
            History::log([
                'history_history_text_id' => HistoryText::USER_VICE_TEAM_OUT,
                'history_team_id' => $viceTeam->team_id,
                'history_user_id' => $this->user->user_id,
            ]);

            $viceTeam->team_vice_id = 0;
            $viceTeam->save();
        }

        Yii::$app->mailer->compose(
            ['html' => 'default-html', 'text' => 'default-text'],
            ['text' => 'Ваша заявка на получение команды одобрена']
        )
            ->setTo($this->user->user_email)
            ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['noReplyName']])
            ->setSubject('Получение команды на сайте Виртуальной Регбийной Лиги')
            ->send();

        return true;
    }
}