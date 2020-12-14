<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\FormatHelper;
use DateTime;
use DateTimeZone;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Transfer
 * @package common\models\db
 *
 * @property int $id
 * @property int $age
 * @property int $cancel
 * @property int $date
 * @property bool $is_to_league
 * @property int $player_id
 * @property int $player_price
 * @property int $power
 * @property int $price_buyer
 * @property int $price_seller
 * @property int $ready
 * @property int $season_id
 * @property int $team_buyer_id
 * @property int $team_seller_id
 * @property int $user_buyer_id
 * @property int $user_seller_id
 * @property int $voted
 *
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Team $teamBuyer
 * @property-read Team $teamSeller
 * @property-read TransferPosition[] $transferPositions
 * @property-read TransferSpecial[] $transferSpecials
 * @property-read TransferVote[] $transferVotes
 * @property-read TransferVote[] $transferVotesMinus
 * @property-read TransferVote[] $transferVotesPlus
 * @property-read User $userBuyer
 * @property-read User $userSeller
 */
class Transfer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['player_id', 'price_seller', 'team_seller_id', 'user_seller_id'], 'required'],
            [['age'], 'integer', 'min' => 1, 'max' => 99],
            [['power', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [
                [
                    'cancel',
                    'user_buyer_id',
                    'user_seller_id',
                    'team_buyer_id',
                    'team_seller_id',
                    'voted',
                ],
                'integer',
                'min' => 0
            ],
            [
                [
                    'player_id',
                    'player_price',
                    'price_buyer',
                    'price_seller',
                    'ready',
                ],
                'integer',
                'min' => 1
            ],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_buyer_id'], 'exist', 'targetRelation' => 'teamBuyer'],
            [['team_seller_id'], 'exist', 'targetRelation' => 'teamSeller'],
            [['user_buyer_id'], 'exist', 'targetRelation' => 'userBuyer'],
            [['user_seller_id'], 'exist', 'targetRelation' => 'userSeller'],
        ];
    }

    /**
     * @return array
     */
    public function alerts(): array
    {
        $result = [
            'success' => [],
            'warning' => [],
            'error' => [],
        ];

        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $teamId = $this->team_buyer_id;
                $userId = $this->user_buyer_id;
            } else {
                $teamId = $this->team_seller_id;
                $userId = $this->user_seller_id;
            }

            if ($userId && $teamId) {
                /**
                 * @var History $history
                 */
                $history = History::find()
                    ->where([
                        'history_text_id' => [
                            HistoryText::USER_MANAGER_TEAM_IN,
                            HistoryText::USER_MANAGER_TEAM_OUT,
                        ],
                        'team_id' => $teamId,
                        'user_id' => $userId,
                    ])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit(1)
                    ->one();
                if ($history) {
                    if (HistoryText::USER_MANAGER_TEAM_OUT === $history->history_text_id) {
                        $result['error'][] = 'Менеджер <span class="strong">' . Html::encode($history->user->login) . '</span> покинул команду <span class="strong">' . $history->team->name . '</span>.';
                    } elseif ($history->date > time() - 2592000) {
                        $result['error'][] = 'Менеджер <span class="strong">' . Html::encode($history->user->login) . '</span> менее 1 месяца в команде.';
                    } elseif ($history->date > time() - 5184000) {
                        $result['warning'][] = 'Менеджер <span class="strong">' . Html::encode($history->user->login) . '</span> менее 2 месяцев в команде.';
                    }
                }

                $user = User::find()
                    ->where(['id' => $userId])
                    ->limit(1)
                    ->one();
                if ($user->date_login < time() - 604800) {
                    $result['error'][] = 'Менеджер <span class="strong">' . Html::encode($user->login) . '</span> больше недели не заходил на сайт.';
                }

                if ($user->date_register > time() - 2592000) {
                    $result['error'][] = 'Менеджер <span class="strong">' . Html::encode($user->login) . '</span> менее 1 месяца в Лиге.';
                } elseif ($user->date_register > time() - 5184000) {
                    $result['warning'][] = 'Менеджер <span class="strong">' . Html::encode($user->login) . '</span> менее 2 месяцев в Лиге.';
                }

                $team = Team::find()
                    ->where(['id' => $teamId])
                    ->limit(1)
                    ->one();

                if ($team->auto_number) {
                    $result['warning'][] = 'Команда <span class="strong">' . $team->name . '</span> сыграла ' . $team->auto_number . ' последних матчей автосоставом.';
                }

                $transfer = self::find()
                    ->where([
                        'or',
                        ['user_buyer_id' => $userId],
                        ['user_seller_id' => $userId],
                    ])
                    ->andWhere(['not', ['cancel' => null]])
                    ->andWhere(['season_id' => Season::getCurrentSeason()])
                    ->count();

                $loan = Loan::find()
                    ->where([
                        'or',
                        ['user_buyer_id' => $userId],
                        ['user_seller_id' => $userId],
                    ])
                    ->andWhere(['not', ['cancel' => null]])
                    ->andWhere(['season_id' => Season::getCurrentSeason()])
                    ->count();

                if ($transfer + $loan) {
                    $result['warning'][] = 'У менеджера <span class="strong">' . Html::encode($user->login) . '</span> в этом сезоне уже отменяли <span class="strong">' . ($transfer + $loan) . ' сделок</span>.';
                }
            }
        }

        if ($this->team_buyer_id && $this->user_buyer_id && $this->team_seller_id && $this->user_seller_id) {
            $transfer = Transfer::find()
                ->where([
                    'or',
                    [
                        'user_buyer_id' => $this->user_buyer_id,
                        'user_seller_id' => $this->user_seller_id,
                    ],
                    [
                        'user_buyer_id' => $this->user_seller_id,
                        'user_seller_id' => $this->user_buyer_id,
                    ],
                ])
                ->andWhere(['cancel' => null])
                ->andWhere(['not', ['ready' => null]])
                ->andWhere(['<', 'ready', $this->ready])
                ->andWhere(['!=', 'id', $this->id])
                ->count();
            $loan = Loan::find()
                ->where([
                    'or',
                    [
                        'loan_user_buyer_id' => $this->user_buyer_id,
                        'loan_user_seller_id' => $this->user_seller_id,
                    ],
                    [
                        'loan_user_buyer_id' => $this->user_seller_id,
                        'loan_user_seller_id' => $this->user_buyer_id,
                    ],
                ])
                ->andWhere(['cancel' => null])
                ->andWhere(['not', ['ready' => null]])
                ->andWhere(['<', 'ready', $this->ready])
                ->count();


            if ($transfer + $loan) {
                $result['warning'][] = 'Менеджеры уже заключали <span class="strong">' . ($transfer + $loan) . ' сделок</span> между собой.';
            }

            $user = User::find()
                ->where(['<', 'date_register', time() - 5184000])
                ->andWhere(['id' => [$this->user_buyer_id, $this->user_seller_id]])
                ->count();

            if (2 === $user) {
                $result['success'][] = 'Оба менеджера достаточно давно играют в Лиге.';
            }
        }

        if (0 === count($result['success'])) {
            unset($result['success']);
        }

        if (0 === count($result['warning'])) {
            unset($result['warning']);
        }

        if (0 === count($result['error'])) {
            unset($result['error']);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function rating(): string
    {
        $returnArray = [
            '<span class="font-green">' . count($this->transferVotesPlus) . '</span>',
            '<span class="font-red">' . count($this->transferVotesMinus) . '</span>',
        ];

        return implode(' | ', $returnArray);
    }

    /**
     * @return string
     */
    public function dealDate(): string
    {
        $today = (new DateTime())
            ->setTimezone(new DateTimeZone('UTC'))
            ->setTime(9, 0)
            ->getTimestamp();

        if ($today < $this->date + 86400 || $today < time()) {
            $today += 86400;
        }

        return FormatHelper::asDate($today);
    }

    /**
     * @return string
     */
    public function position(): string
    {
        $result = [];
        foreach ($this->transferPositions as $transferPosition) {
            $result[] = Html::tag(
                'span',
                $transferPosition->position->name,
                ['title' => $transferPosition->position->text]
            );
        }
        return implode('/', $result);
    }

    /**
     * @return string
     */
    public function special(): string
    {
        $result = [];
        foreach ($this->transferSpecials as $transferSpecial) {
            $result[] = Html::tag(
                'span',
                $transferSpecial->special->name . $transferSpecial->level,
                ['title' => $transferSpecial->special->text]
            );
        }
        return implode(' ', $result);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamBuyer(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_buyer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamSeller(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_seller_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransferPositions(): ActiveQuery
    {
        return $this->hasMany(TransferPosition::class, ['transfer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransferSpecials(): ActiveQuery
    {
        return $this->hasMany(TransferSpecial::class, ['transfer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransferVotes(): ActiveQuery
    {
        return $this->hasMany(TransferVote::class, ['transfer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransferVotesMinus(): ActiveQuery
    {
        return $this->getTransferVotes()->andWhere(['<', 'rating', 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransferVotesPlus(): ActiveQuery
    {
        return $this->getTransferVotes()->andWhere(['>', 'rating', 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBuyer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_buyer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserSeller(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_seller_id']);
    }
}
