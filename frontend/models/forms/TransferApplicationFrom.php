<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\TransferApplication;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class TransferApplicationFrom
 * @package frontend\models
 *
 * @property bool $off
 * @property Player $player
 * @property Team $team
 */
class TransferApplicationFrom extends Model
{
    public $off;
    public $player;
    public $team;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['off'], 'boolean'],
            [['off'], 'required'],
        ];
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if (!$this->player->transfer) {
            return false;
        }

        $transferApplication = TransferApplication::find()
            ->where([
                'transfer_id' => $this->player->transfer->id,
                'team_id' => $this->team->id,
            ])
            ->limit(1)
            ->one();
        if (!$transferApplication) {
            Yii::$app->session->setFlash('error', 'Заявка выбрана неправильно.');
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $transferApplication->delete();
            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', 'Заявка успешно удалена.');
        } catch (Throwable $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
