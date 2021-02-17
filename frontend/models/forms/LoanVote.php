<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\LoanComment;
use common\models\db\LoanVote as VoteModel;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class LoanVote
 * @package frontend\models
 *
 * @property string $comment
 * @property int $loanId
 * @property int $vote
 */
class LoanVote extends Model
{
    public $comment;
    public $loanId;
    public $vote;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $model = VoteModel::find()
            ->where(['loan_id' => $this->loanId, 'user_id' => Yii::$app->user->id])
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
            'vote' => Yii::t('frontend', 'models.forms.loan-vote.label.vote'),
            'comment' => Yii::t('frontend', 'models.forms.loan-vote.label.comment'),
        ];
    }

    /**
     * @return bool
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
            ->where(['loan_id' => $this->loanId, 'user_id' => $user->id])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new VoteModel();
            $model->loan_id = $this->loanId;
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
                        $model = new LoanComment();
                        $model->text = $this->comment;
                        $model->loan_id = $this->loanId;
                        $model->user_id = $user->id;
                        $model->save();
                    }
                }
            }

            $checkFinance = Finance::find()
                ->where(['loan_id' => $this->loanId, 'user_id' => $user->id])
                ->count();
            if (!$checkFinance) {
                $sum = 1000;

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_DEAL_CHECK,
                    'loan_id' => $this->loanId,
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
