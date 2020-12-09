<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\PlayerSpecial;
use common\models\db\Training;
use Exception;
use Yii;

/**
 * Class UpdateTraining
 * @package console\models\generator
 *
 * @property Training $training
 */
class UpdateTraining
{
    /**
     * @var Training $training
     */
    private $training;

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function execute(): void
    {
        $this->increasePercent();

        $trainingArray = Training::find()
            ->where(['>=', 'percent', 100])
            ->andWhere(['ready' => null])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($trainingArray as $training) {
            $this->training = $training;

            if ($this->training->is_power) {
                $this->power();
            } elseif ($this->training->position_id) {
                $this->position();
            } elseif ($this->training->special_id) {
                $this->special();
            }
        }

        $this->ready();
    }

    /**
     * @return void
     * @throws \yii\db\Exception
     */
    private function increasePercent(): void
    {
        $sql = "UPDATE `training`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_training`
                ON `base_training_id`=`base_training`.`id`
                LEFT JOIN `player`
                ON `player_id`=`player`.`id`
                SET `percent`=`percent`+`training_speed_min`+(`training_speed_max`-`training_speed_min`)/2*RAND()+`training_ability`
                WHERE `ready` IS NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @throws Exception
     */
    private function power(): void
    {
        Player::updateAllCounters(
            ['power_nominal' => 1],
            ['id' => $this->training->player_id]
        );

        History::log([
            'history_text_id' => HistoryText::PLAYER_TRAINING_POINT,
            'player_id' => $this->training->player_id,
        ]);
    }

    /**
     * @throws Exception
     */
    private function position(): void
    {
        $model = new PlayerPosition();
        $model->player_id = $this->training->player_id;
        $model->position_id = $this->training->position_id;
        $model->save();

        History::log([
            'history_text_id' => HistoryText::PLAYER_TRAINING_POSITION,
            'player_id' => $this->training->player_id,
            'position_id' => $this->training->position_id,
        ]);
    }

    /**
     * @throws Exception
     */
    private function special(): void
    {
        $model = PlayerSpecial::find()->where([
            'player_id' => $this->training->player_id,
            'special_id' => $this->training->special_id,
        ])->limit(1)->one();
        if (!$model) {
            $model = new PlayerSpecial();
            $model->player_id = $this->training->player_id;
            $model->special_id = $this->training->special_id;
            $model->level = 1;
        } else {
            $model->level++;
        }
        $model->save();

        History::log([
            'history_text_id' => HistoryText::PLAYER_TRAINING_SPECIAL,
            'player_id' => $this->training->player_id,
            'special_id' => $this->training->special_id,
        ]);
    }

    /**
     *
     */
    private function ready(): void
    {
        Training::updateAll(
            ['percent' => 100, 'ready' => time()],
            ['and', ['>=', 'percent', 100], ['ready' => null]]
        );
    }
}