<?php

namespace frontend\models\forms;

use common\models\db\National;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\Position;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class NationalPlayer
 * @package common\models\forms
 *
 * @property National $national
 * @property array $player
 * @property array $playerArray
 */
class NationalPlayer extends Model
{
    /**
     * @var National $national
     */
    public $national;

    /**
     * @var array $player
     */
    public $player;

    /**
     * @var array $playerArray
     */
    public $playerArray;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['player'], 'checkPlayer'],
        ];
    }

    /**
     * @param string $attribute
     */
    public function checkPlayer(string $attribute)
    {
        if (count($this->$attribute) !== 10) {
            $this->addError('player', Yii::t('frontend', 'models.forms.national-player.error'));
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
                $this->addError('player', Yii::t('frontend', 'models.forms.national-player.error'));
            }

            foreach ($playerArray as $playerId) {
                $player = Player::find()
                    ->andWhere([
                        'id' => $playerId,
                        'country_id' => $this->national->federation->country_id,
                        'national_id' => [null, $this->national->id],
                    ])
                    ->andWhere([
                        'id' => PlayerPosition::find()
                            ->select(['player_id'])
                            ->where(['position_id' => $positionId])
                    ])
                    ->exists();
                if (!$player) {
                    $this->addError('player', Yii::t('frontend', 'models.forms.national-player.error'));
                }
            }
        }

        $this->playerArray = $formPlayerArray;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function savePlayer(): bool
    {
        $this->loadPlayer();

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        Player::updateAll(['national_id' => null], ['national_id' => $this->national->id]);
        foreach ($this->playerArray as $playerId) {
            $model = Player::find()
                ->where(['id' => $playerId])
                ->limit(1)
                ->one();
            if ($model) {
                $model->national_id = $this->national->id;
                $model->save(true, ['national_id']);
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function loadPlayer(): void
    {
        $players = Player::find()
            ->select(['id'])
            ->where(['national_id' => $this->national->id])
            ->column();
        foreach ($players as $playerId) {
            $this->playerArray[] = (int)$playerId;
        }
    }
}
