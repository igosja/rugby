<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Throwable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

/**
 * Class ElectionNationalApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $election_national_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read ElectionNational $electionNational
 * @property-read ElectionNationalPlayer[] $electionNationalPlayers
 * @property-read ElectionNationalVote[] $electionNationalVotes
 * @property-read User $user
 */
class ElectionNationalApplication extends AbstractActiveRecord
{
    /**
     * @var array $player
     */
    public array $player = [];

    /**
     * @var array $playerArray
     */
    public array $playerArray = [];

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_application}}';
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
            [['election_national_id', 'text', 'user_id'], 'required'],
            [['election_national_id', 'user_id'], 'integer', 'min' => 0],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['player'], 'checkPlayer'],
            [['election_national_id'], 'exist', 'targetRelation' => 'electionNational'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete(): bool
    {
        foreach ($this->electionNationalPlayers as $electionNationalPlayer) {
            $electionNationalPlayer->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @param string $attribute
     */
    public function checkPlayer(string $attribute)
    {
        if (count($this->$attribute) !== 10) {
            $this->addError('player', Yii::t('common', 'models.db.election-national-application.player.error'));
        }

        $formPlayerArray = [];
        foreach ($this->$attribute as $positionId => $playerArray) {
            $playerArray = array_diff($playerArray, [0]);
            $formPlayerArray = ArrayHelper::merge($formPlayerArray, $playerArray);

            $limit = 2;
            if (in_array($positionId, [Position::CENTRE, Position::FLANKER, Position::PROP, Position::LOCK, Position::WING], true)) {
                $limit = 4;
            }

            if (count($playerArray) !== $limit) {
                $this->addError('player', Yii::t('common', 'models.db.election-national-application.player.error'));
            }

            foreach ($playerArray as $playerId) {
                $player = Player::find()
                    ->andWhere([
                        'id' => $playerId,
                        'country_id' => $this->electionNational->federation->country_id,
                        'national_id' => [null, $this->electionNational->national],
                    ])
                    ->andWhere([
                        'id' => PlayerPosition::find()
                            ->select(['player_id'])
                            ->where(['position_id' => $positionId])
                    ])
                    ->exists();
                if (!$player) {
                    $this->addError('player', Yii::t('common', 'models.db.election-national-application.player.error'));
                }
            }
        }

        $this->playerArray = $formPlayerArray;
    }

    /**
     * @return bool
     */
    public function saveApplication(): bool
    {
        $this->loadPlayer();

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $this->save();

        ElectionNationalPlayer::deleteAll(['election_national_application_id' => $this->id]);

        foreach ($this->playerArray as $playerId) {
            $model = new ElectionNationalPlayer();
            $model->player_id = $playerId;
            $model->election_national_application_id = $this->id;
            $model->save();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function loadPlayer(): bool
    {
        $this->playerArray = [];
        foreach ($this->electionNationalPlayers as $electionNationalPlayer) {
            $this->playerArray[] = $electionNationalPlayer->player_id;
        }
        return true;
    }

    /**
     * @return int
     */
    public function playerPower(): int
    {
        $result = 0;

        foreach ($this->electionNationalPlayers as $electionNationalPlayer) {
            $result += $electionNationalPlayer->player->power_nominal_s;
        }

        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNational(): ActiveQuery
    {
        return $this->hasOne(ElectionNational::class, ['id' => 'election_national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalPlayers(): ActiveQuery
    {
        return $this
            ->hasMany(ElectionNationalPlayer::class, ['election_national_application_id' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalVotes(): ActiveQuery
    {
        return $this->hasMany(ElectionNationalVote::class, ['election_national_application_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
