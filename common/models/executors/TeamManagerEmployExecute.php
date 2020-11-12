<?php

// TODO refactor

namespace common\models\executors;

use common\components\interfaces\ExecuteInterface;
use common\models\db\HistoryText;
use common\models\db\Team;
use common\models\db\User;
use frontend\models\executors\HistoryLogExecutor;
use Yii;

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
    private Team $team;

    /**
     * @var User $user
     */
    private User $user;

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
     */
    public function execute(): bool
    {
        $this->team->user_id = $this->user->id;
        $this->team->save();

        (new HistoryLogExecutor(
            [
                'history_text_id' => HistoryText::USER_MANAGER_TEAM_IN,
                'team_id' => $this->team->id,
                'user_id' => $this->user->id,
            ]
        ))->execute();

        /**
         * @var Team[] $viceTeamArray
         */
        $viceTeamArray = Team::find()
            ->joinWith(['stadium.city.country'])
            ->where(['vice_user_id' => $this->user->id, 'country.id' => $this->team->stadium->city->country->id])
            ->orderBy(['team.id' => SORT_ASC])
            ->all();
        foreach ($viceTeamArray as $viceTeam) {
            (new HistoryLogExecutor(
                [
                    'history_text_id' => HistoryText::USER_VICE_TEAM_OUT,
                    'team_id' => $viceTeam->id,
                    'user_id' => $this->user->id,
                ]
            ))->execute();

            $viceTeam->vice_user_id = 0;
            $viceTeam->save();
        }

        Yii::$app->mailer->compose(
            ['html' => 'default-html', 'text' => 'default-text'],
            ['text' => 'Ваша заявка на получение команды одобрена']
        )
            ->setTo($this->user->email)
            ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['noReplyName']])
            ->setSubject('Получение команды на сайте Виртуальной Регбийной Лиги')
            ->send();

        return true;
    }
}