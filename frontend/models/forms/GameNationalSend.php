<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\Mood;
use common\models\db\National;
use common\models\db\Player;
use common\models\db\Rudeness;
use common\models\db\Style;
use common\models\db\Tactic;
use Exception;
use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Class GameNationalSend
 * @package frontend\models
 */
class GameNationalSend extends Model
{
    public $captain;

    /**
     * @var Game $game
     */
    public $game;
    public $home;
    public $line = [];
    public $mood = Mood::NORMAL;
    public $name;
    public $rudeness = Rudeness::NORMAL;
    public $style = Style::NORMAL;
    public $tactic = Tactic::NORMAL;
    public $ticket = 20;

    /**
     * @var National $national
     */
    public $national;

    /**
     * GameSend constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!$this->game || !$this->national) {
            return;
        }
        /**
         * @var Lineup[] $lineupArray
         */
        $lineupArray = Lineup::find()
            ->where(['game_id' => $this->game->id, 'national_id' => $this->national->id])
            ->all();
        foreach ($lineupArray as $item) {
            $this->line[$item->position_id] = $item->player_id;
            if ($item->is_captain) {
                $this->captain = $item->player_id;
            }
        }

        $this->ticket = $this->game->ticket_price ?: $this->ticket;

        if ($this->game->guest_national_id === $this->national->id) {
            $this->home = false;
            $this->mood = $this->game->guest_mood_id ?: $this->mood;
            $this->rudeness = $this->game->guest_rudeness_id ?: $this->rudeness;
            $this->style = $this->game->guest_style_id ?: $this->style;
            $this->tactic = $this->game->guest_tactic_id ?: $this->tactic;
        } else {
            $this->home = true;
            $this->mood = $this->game->home_mood_id ?: $this->mood;
            $this->rudeness = $this->game->home_rudeness_id ?: $this->rudeness;
            $this->style = $this->game->home_style_id ?: $this->style;
            $this->tactic = $this->game->home_tactic_id ?: $this->tactic;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
            [
                [
                    'captain',
                    'mood',
                    'rudeness',
                    'style',
                    'tactic',
                ],
                'integer',
                'min' => 1,
            ],
            [['ticket'], 'integer', 'min' => Game::TICKET_PRICE_MIN, 'max' => Game::TICKET_PRICE_MAX],
            [
                ['line'],
                'each',
                'rule' => [
                    'exist',
                    'targetClass' => Player::class,
                    'targetAttribute' => 'id',
                    'filter' => function (Query $query) {
                        $query->andWhere(['national_id' => $this->national->id]);
                    }
                ]
            ],
            [['captain'], 'in', 'range' => function () {
                return $this->line;
            }, 'enableClientValidation' => false],
            [['mood'], 'exist', 'targetClass' => Mood::class, 'targetAttribute' => 'id'],
            [['rudeness'], 'exist', 'targetClass' => Rudeness::class, 'targetAttribute' => 'id'],
            [['style'], 'exist', 'targetClass' => Style::class, 'targetAttribute' => 'id'],
            [['tactic'], 'exist', 'targetClass' => Tactic::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function saveLineup(): bool
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        if ((Mood::SUPER === $this->mood && $this->national->mood_super <= 0) || (Mood::REST === $this->mood && $this->national->mood_rest <= 0)) {
            $this->mood = Mood::NORMAL;
        }

        if ($this->home) {
            $this->game->home_mood_id = $this->mood;
            $this->game->home_rudeness_id = $this->rudeness;
            $this->game->home_style_id = $this->style;
            $this->game->home_tactic_id = $this->tactic;
            $this->game->ticket_price = $this->ticket;
            $this->game->home_user_id = Yii::$app->user->id;
        } else {
            $this->game->guest_mood_id = $this->mood;
            $this->game->guest_rudeness_id = $this->rudeness;
            $this->game->guest_style_id = $this->style;
            $this->game->guest_tactic_id = $this->tactic;
            $this->game->guest_user_id = Yii::$app->user->id;
        }
        $this->game->save();

        foreach ($this->line as $positionId => $playerId) {
            $lineup = Lineup::find()
                ->where([
                    'game_id' => $this->game->id,
                    'position_id' => $positionId,
                    'national_id' => $this->national->id,
                ])
                ->limit(1)
                ->one();
            if (!$lineup) {
                $lineup = new Lineup();
                $lineup->game_id = $this->game->id;
                $lineup->position_id = $positionId;
                $lineup->national_id = $this->national->id;
            }
            $lineup->is_captain = false;
            $lineup->player_id = $positionId;
            $lineup->save();
        }

        foreach ($this->line as $positionId => $playerId) {
            $lineup = Lineup::find()
                ->where([
                    'game_id' => $this->game->id,
                    'position_id' => $positionId,
                    'national_id' => $this->national->id,
                ])
                ->limit(1)
                ->one();
            if ($this->captain === $playerId) {
                $lineup->is_captain = true;
            } else {
                $lineup->is_captain = false;
            }
            $lineup->player_id = $playerId;
            $lineup->save();
        }

        return true;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}
