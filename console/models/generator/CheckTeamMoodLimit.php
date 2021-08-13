<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Mood;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use Exception;

/**
 * Class CheckTeamMoodLimit
 * @package console\models\generator
 *
 * @property Game $game
 */
class CheckTeamMoodLimit
{
    /**
     * @var Game $game
     */
    private $game;

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->with(['guestTeam', 'homeTeam', 'guestNational', 'homeNational', 'schedule'])
            ->where(['played' => null])
            ->andWhere(['or', ['!=', 'home_mood_id', Mood::NORMAL], ['!=', 'guest_mood_id', Mood::NORMAL]])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            $this->game = $game;

            if ($this->isFriendly()) {
                $this->checkFriendly();
                continue;
            }

            if ($this->game->home_team_id) {
                $this->checkTeam();
            } else {
                $this->checkNational();
            }
        }
    }

    /**
     * @return bool
     */
    private function isFriendly(): bool
    {
        return TournamentType::FRIENDLY === $this->game->schedule->tournament_type_id;
    }

    /**
     * @throws Exception
     */
    private function checkFriendly(): void
    {
        $this->game->home_mood_id = Mood::NORMAL;
        $this->game->guest_mood_id = Mood::NORMAL;
        $this->game->save();
    }

    /**
     * @throws Exception
     */
    private function checkTeam(): void
    {
        if (Mood::SUPER === $this->game->home_mood_id) {
            if ($this->game->homeTeam->mood_super <= 0) {
                $this->game->home_mood_id = Mood::NORMAL;
                $this->game->save(true, ['home_mood_id']);
            } else {
                $this->game->homeTeam->mood_super--;
                $this->game->homeTeam->save(true, ['mood_super']);
            }
        } elseif (Mood::REST === $this->game->home_mood_id) {
            if ($this->game->homeTeam->mood_rest <= 0) {
                $this->game->home_mood_id = Mood::NORMAL;
                $this->game->save(true, ['home_mood_id']);
            } else {
                $this->game->homeTeam->mood_rest--;
                $this->game->homeTeam->save(true, ['mood_rest']);
            }
        }

        if (Mood::SUPER === $this->game->guest_mood_id) {
            if ($this->game->guestTeam->mood_super <= 0) {
                $this->game->guest_mood_id = Mood::NORMAL;
                $this->game->save(true, ['guest_mood_id']);
            } else {
                $this->game->guestTeam->mood_super--;
                $this->game->guestTeam->save(true, ['mood_super']);
            }
        } elseif (Mood::REST === $this->game->guest_mood_id) {
            if ($this->game->guestTeam->mood_rest <= 0) {
                $this->game->guest_mood_id = Mood::NORMAL;
                $this->game->save(true, ['guest_mood_id']);
            } else {
                $this->game->guestTeam->mood_rest--;
                $this->game->guestTeam->save(true, ['mood_rest']);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function checkNational(): void
    {
        if (Mood::SUPER === $this->game->home_mood_id) {
            if ($this->game->homeNational->mood_super <= 0) {
                $this->game->home_mood_id = Mood::NORMAL;
                $this->game->save(true, ['home_mood_id']);
            } else {
                $this->game->homeNational->mood_super--;
                $this->game->homeNational->save(true, ['home_mood_id']);
            }
        } elseif (Mood::REST === $this->game->home_mood_id) {
            if ($this->game->homeNational->mood_rest <= 0) {
                $this->game->home_mood_id = Mood::NORMAL;
                $this->game->save(true, ['mood_rest']);
            } else {
                $this->game->homeNational->mood_rest--;
                $this->game->homeNational->save(true, ['mood_rest']);
            }
        }

        if (Mood::SUPER === $this->game->guest_mood_id) {
            if ($this->game->guestNational->mood_super <= 0) {
                $this->game->guest_mood_id = Mood::NORMAL;
                $this->game->save(true, ['guest_mood_id']);
            } else {
                $this->game->guestNational->mood_super--;
                $this->game->guestNational->save(true, ['mood_super']);
            }
        } elseif (Mood::REST === $this->game->guest_mood_id) {
            if ($this->game->guestNational->mood_rest <= 0) {
                $this->game->guest_mood_id = Mood::NORMAL;
                $this->game->save(true, ['guest_mood_id']);
            } else {
                $this->game->guestNational->mood_rest--;
                $this->game->guestNational->save(true, ['mood_rest']);
            }
        }
    }
}
