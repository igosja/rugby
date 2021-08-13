<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Team;
use common\models\db\User;
use common\models\db\UserHoliday;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class ChangePassword
 * @package frontend\models
 *
 * @property bool $isHoliday
 * @property User $user
 */
class Holiday extends Model
{
    public $isHoliday;
    private $user;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['isHoliday'], 'boolean'],
        ];
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function change(): bool
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if ($this->isHoliday && !$this->user->activeUserHoliday) {
            $userHoliday = new UserHoliday();
            $userHoliday->date_start = time();
            $userHoliday->user_id = $this->user->id;
            $userHoliday->save();
        }
        if (!$this->isHoliday && $this->user->activeUserHoliday) {
            $this->user->activeUserHoliday->date_end = time();
            $this->user->activeUserHoliday->save();
        }

        $vice = Yii::$app->request->post('vice');
        if (!$vice) {
            return true;
        }

        foreach ($vice as $teamId => $userId) {
            /**
             * @var Team $team
             */
            $team = Team::find()
                ->where(['id' => $teamId, 'user_id' => $this->user->id])
                ->limit(1)
                ->one();
            if (!$team) {
                continue;
            }
            $userId = (int)$userId;
            if (!$this->isHoliday && !$this->user->isVip()) {
                $userId = 0;
            }
            $user = User::find()
                ->where(['>', 'date_login', time() - 604800])
                ->andWhere(['id' => $userId])
                ->andWhere([
                    'not',
                    [
                        'id' => Team::find()
                            ->joinWith(['stadium.city.country'])
                            ->select(['user_id'])
                            ->where(['country.id' => $team->stadium->city->country->id])
                    ]
                ])
                ->andWhere([
                    'not',
                    [
                        'id' => Team::find()
                            ->joinWith(['stadium.city.country'])
                            ->select(['vice_user_id'])
                            ->where(['country.id' => $team->stadium->city->country->id])
                            ->andWhere(['!=', 'team.id', $team->id])
                    ]
                ])
                ->limit(1)
                ->one();
            if (!$user) {
                $userId = 0;
            }

            if ($team->vice_user_id && $userId !== $team->vice_user_id) {
                History::log([
                    'history_text_id' => HistoryText::USER_VICE_TEAM_OUT,
                    'team_id' => $team->id,
                    'user_id' => $team->vice_user_id,
                ]);
            }
            if ($userId && $userId !== $team->vice_user_id) {
                History::log([
                    'history_text_id' => HistoryText::USER_VICE_TEAM_IN,
                    'team_id' => $team->id,
                    'user_id' => $userId,
                ]);
            }

            $team->vice_user_id = $userId;
            $team->save(true, ['vice_user_id']);
        }

        return true;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
        if ($this->user->activeUserHoliday) {
            $this->isHoliday = true;
        }
    }
}
