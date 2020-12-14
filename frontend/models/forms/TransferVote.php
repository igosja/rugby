<?php

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\TransferComment;
use common\models\db\TransferVote as VoteModel;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class TransferVote
 * @package frontend\models
 *
 * @property string $comment
 * @property int $transferId
 * @property int $vote
 */
class TransferVote extends Model
{
    public $comment;
    public $transferId;
    public $vote;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $model = VoteModel::find()
            ->where(['transfer_id' => $this->transferId, 'user_id' => Yii::$app->user->id])
            ->limit(1)
            ->one();
        if ($model) {
            $this->vote = $model->rating;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['vote'], 'in', 'range' => [-1, 1]],
            [['vote'], 'required'],
            [['comment'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'vote' => 'Оценка',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveVote(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $model = VoteModel::find()
            ->where(['transfer_id' => $this->transferId, 'user_id' => $user->id])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new VoteModel();
            $model->transfer_id = $this->transferId;
            $model->user_id = $user->id;
        }

        try {
            $model->rating = $this->vote;
            $model->save();

            if ($this->comment) {
                /**
                 * @var UserBlock $userBlock
                 */
                $userBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT_DEAL)->one();
                if (!$userBlock || $userBlock->date < time()) {
                    $userBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT)->one();
                    if (!$userBlock || $userBlock->date < time()) {
                        $model = new TransferComment();
                        $model->text = $this->comment;
                        $model->transfer_id = $this->transferId;
                        $model->user_id = $user->id;
                        $model->save();
                    }
                }
            }

            $checkFinance = Finance::find()
                ->where(['transfer_id' => $this->transferId, 'user_id' => $user->id])
                ->count();
            if (!$checkFinance) {
                $sum = 1000;

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_DEAL_CHECK,
                    'transfer_id' => $this->transferId,
                    'user_id' => $user->id,
                    'value' => $sum,
                    'value_after' => $user->finance + $sum,
                    'value_before' => $user->finance,
                ]);

                $user->finance += $sum;
                $user->save(true, ['finance']);
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return false;
        }

        return true;
    }
}
