<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class TransferFrom
 * @package frontend\models
 *
 * @property bool $off
 * @property Player $player
 * @property Team $team
 * @property TransferApplication[] $transferApplicationArray
 */
class TransferFrom extends Model
{
    public $off;
    public $player;
    public $team;
    public $transferApplicationArray;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->transferApplicationArray = TransferApplication::find()
            ->where(['id' => ($this->player->transfer->id ?? 0)])
            ->orderBy(['price' => SORT_DESC, 'date' => SORT_ASC])
            ->all();
    }

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

        $transfer = Transfer::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->one();
        if (!$transfer) {
            return false;
        }

        if ($this->team->finance < 0) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('frontend', 'models.forms.transfer.execute.error')
            );
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $transfer->delete();
            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', Yii::t('frontend', 'models.forms.transfer.execute.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
