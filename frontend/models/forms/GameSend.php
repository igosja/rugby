<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\LineupTemplate;
use common\models\db\Mood;
use common\models\db\Player;
use common\models\db\Rudeness;
use common\models\db\Style;
use common\models\db\Tactic;
use common\models\db\Team;
use common\models\db\TournamentType;
use Exception;
use frontend\controllers\AbstractController;
use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Class GameSend
 * @package frontend\models
 */
class GameSend extends Model
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
     * @var Team $team
     */
    public $team;

    /**
     * GameSend constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!$this->game || !$this->team) {
            return;
        }
        /**
         * @var Lineup[] $lineupArray
         */
        $lineupArray = Lineup::find()
            ->where(['game_id' => $this->game->id, 'team_id' => $this->team->id])
            ->all();
        foreach ($lineupArray as $item) {
            $this->line[$item->position_id] = $item->player_id;
            if ($item->is_captain) {
                $this->captain = $item->player_id;
            }
        }

        $this->ticket = $this->game->ticket_price ?: $this->ticket;

        if ($this->game->guest_team_id === $this->team->id) {
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
                        $query->andWhere([
                            'or',
                            ['team_id' => $this->team->id, 'loan_team_id' => null],
                            ['loan_team_id' => $this->team->id]
                        ]);
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

        if ((Mood::SUPER === $this->mood && $this->team->mood_super <= 0) || (Mood::REST === $this->mood && $this->team->mood_rest <= 0) || (Mood::NORMAL !== $this->mood && TournamentType::FRIENDLY === $this->game->schedule->tournament_type_id)) {
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
                    'team_id' => $this->team->id,
                ])
                ->limit(1)
                ->one();
            if (!$lineup) {
                $lineup = new Lineup();
                $lineup->game_id = $this->game->id;
                $lineup->position_id = $positionId;
                $lineup->team_id = $this->team->id;
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
                    'team_id' => $this->team->id,
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
     * @return bool
     * @throws Exception
     */
    public function saveLineupTemplate()
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $gk_1_id = $this->line[0][0];
        $gk_2_id = $this->line[1][0];
        $ld_1_id = $this->line[1][1];
        $rd_1_id = $this->line[1][2];
        $lw_1_id = $this->line[1][3];
        $cf_1_id = $this->line[1][4];
        $rw_1_id = $this->line[1][5];
        $ld_2_id = $this->line[2][1];
        $rd_2_id = $this->line[2][2];
        $lw_2_id = $this->line[2][3];
        $cf_2_id = $this->line[2][4];
        $rw_2_id = $this->line[2][5];
        $ld_3_id = $this->line[3][1];
        $rd_3_id = $this->line[3][2];
        $lw_3_id = $this->line[3][3];
        $cf_3_id = $this->line[3][4];
        $rw_3_id = $this->line[3][5];
        $ld_4_id = $this->line[4][1];
        $rd_4_id = $this->line[4][2];
        $lw_4_id = $this->line[4][3];
        $cf_4_id = $this->line[4][4];
        $rw_4_id = $this->line[4][5];

        $model = LineupTemplate::find()
            ->where(['lineup_template_team_id' => $controller->myTeamOrVice->team_id, 'lineup_template_name' => $this->name])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new LineupTemplate();
            $model->lineup_template_name = $this->name ?: FormatHelper::asDateTime(time());
            $model->lineup_template_team_id = $controller->myTeamOrVice->team_id;
        }
        $model->lineup_template_captain = $this->captain;
        $model->lineup_template_national_id = 0;
        $model->lineup_template_player_cf_1 = $cf_1_id;
        $model->lineup_template_player_cf_2 = $cf_2_id;
        $model->lineup_template_player_cf_3 = $cf_3_id;
        $model->lineup_template_player_cf_4 = $cf_4_id;
        $model->lineup_template_player_gk_1 = $gk_1_id;
        $model->lineup_template_player_gk_2 = $gk_2_id;
        $model->lineup_template_player_ld_1 = $ld_1_id;
        $model->lineup_template_player_ld_2 = $ld_2_id;
        $model->lineup_template_player_ld_3 = $ld_3_id;
        $model->lineup_template_player_ld_4 = $ld_4_id;
        $model->lineup_template_player_lw_1 = $lw_1_id;
        $model->lineup_template_player_lw_2 = $lw_2_id;
        $model->lineup_template_player_lw_3 = $lw_3_id;
        $model->lineup_template_player_lw_4 = $lw_4_id;
        $model->lineup_template_player_rd_1 = $rd_1_id;
        $model->lineup_template_player_rd_2 = $rd_2_id;
        $model->lineup_template_player_rd_3 = $rd_3_id;
        $model->lineup_template_player_rd_4 = $rd_4_id;
        $model->lineup_template_player_rw_1 = $rw_1_id;
        $model->lineup_template_player_rw_2 = $rw_2_id;
        $model->lineup_template_player_rw_3 = $rw_3_id;
        $model->lineup_template_player_rw_4 = $rw_4_id;
        $model->lineup_template_rudeness_id_1 = $this->rudeness_1;
        $model->lineup_template_rudeness_id_2 = $this->rudeness_2;
        $model->lineup_template_rudeness_id_3 = $this->rudeness_3;
        $model->lineup_template_rudeness_id_4 = $this->rudeness_4;
        $model->lineup_template_style_id_1 = $this->style_1;
        $model->lineup_template_style_id_2 = $this->style_2;
        $model->lineup_template_style_id_3 = $this->style_3;
        $model->lineup_template_style_id_4 = $this->style_4;
        $model->lineup_template_tactic_id_1 = $this->tactic_1;
        $model->lineup_template_tactic_id_2 = $this->tactic_2;
        $model->lineup_template_tactic_id_3 = $this->tactic_3;
        $model->lineup_template_tactic_id_4 = $this->tactic_4;
        return $model->save();
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('frontend', 'models.forms.game-send.label.name'),
        ];
    }
}
