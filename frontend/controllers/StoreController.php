<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Money;
use common\models\db\MoneyText;
use common\models\db\Payment;
use common\models\db\User;
use DateTime;
use Exception;
use frontend\models\forms\UserTransferMoney;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

/**
 * Class StoreController
 * @package frontend\controllers
 */
class StoreController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'payment', 'history', 'finance'],
                'rules' => [
                    [
                        'actions' => ['index', 'payment', 'history', 'finance'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws Exception
     */
    public function beforeAction($action): bool
    {
        if (in_array($action->id, ['success', 'error', 'result'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $bonusText = self::getStoreDiscountText();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.store.index.title'));
        return $this->render('index', [
            'bonusText' => $bonusText,
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * @return string
     */
    public static function getStoreDiscountText(): string
    {
        try {
            $now = (new DateTime())->getTimestamp();
            $discountDates = self::getDiscountDates();

            foreach ($discountDates as $key => $discountDate) {
                if ($now >= $discountDate[0] && $now <= $discountDate[1]) {
                    $result = '';
                    if (in_array($key, ['newYear1', 'newYear2'])) {
                        $result = Yii::t('frontend', 'controllers.store.discount.new-year');
                    }
                    return $result . Yii::t('frontend', 'controllers.store.discount');
                }
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        return '';
    }

    /**
     * @throws Exception
     */
    private static function getDiscountDates(): array
    {
        return [
            'newYear1' => [
                (new DateTime(date('Y') . '-01-01 00:00:00'))->getTimestamp(),
                (new DateTime(date('Y') . '-01-07 23:59:59'))->getTimestamp()
            ],
            'newYear2' => [
                (new DateTime(date('Y') . '-12-25 00:00:00'))->getTimestamp(),
                (new DateTime(date('Y') . '-12-31 23:59:59'))->getTimestamp()
            ],
        ];
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionPayment()
    {
        $model = new Payment();
        $model->log = Json::encode($_REQUEST);
        $model->status = Payment::PAID;
        $model->user_id = $this->user->id;
        if (false && $model->load(Yii::$app->request->post()) && $model->save()) {
            Money::log([
                'money_text_id' => MoneyText::INCOME_ADD_FUNDS,
                'user_id' => $model->user_id,
                'value' => $model->sum,
                'value_after' => $model->user->money + $model->sum,
                'value_before' => $model->user->money,
            ]);

            $model->user->money += $model->sum;
            $model->user->save(true, ['money']);

            if ($model->user->referrer_user_id) {
                $sum = round($model->sum / 10, 2);

                Money::log([
                    'money_text_id' => MoneyText::INCOME_REFERRAL,
                    'user_id' => $model->user->referrer_user_id,
                    'value' => $sum,
                    'value_after' => $model->user->referrerUser->money + $sum,
                    'value_before' => $model->user->referrerUser->money,
                ]);

                $model->user->referrerUser->money += $sum;
                $model->user->referrerUser->save(true, ['money']);
            }

            return $this->redirect(['index']);
            return $this->redirect($model->paymentUrl());
        }

        $user = $this->user;

        $bonusArray = $this->getBonusArray();
        $bonus = $this->paymentBonus($user->id);

        $bonusLine = [];

        foreach ($bonusArray as $item) {
            if ($bonus === $item) {
                $bonusLine[] = '<span class="strong">' . $item . '%</span>';
            } else {
                $bonusLine[] = $item . '%';
            }
        }

        $bonusLine = implode(' | ', $bonusLine);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.store.payment.title'));

        return $this->render('payment', [
            'bonusLine' => $bonusLine,
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * @return array
     */
    private function getBonusArray(): array
    {
        return [0 => 0, 10 => 2, 25 => 4, 50 => 6, 75 => 8, 100 => 10, 200 => 15, 300 => 20, 500 => 25];
    }

    /**
     * @param int $userId
     * @return int
     */
    private function paymentBonus(int $userId): int
    {
        $paymentSum = Payment::find()
            ->where(['user_id' => $userId, 'status' => Payment::PAID])
            ->sum('sum');

        $result = 0;
        $bonusArray = $this->getBonusArray();
        foreach ($bonusArray as $sum => $bonus) {
            if ($paymentSum > $sum) {
                $result = $bonus;
            }
            if ($paymentSum <= $sum) {
                return $result;
            }
        }

        return end($bonusArray);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionSend()
    {
        $user = $this->user;
        $model = new UserTransferMoney(['user' => $user]);
        if ($model->execute()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.store.send.success'));
            return $this->refresh();
        }

        $userArray = User::find()
            ->andWhere(['!=', 'id', 0])
            ->andWhere(['!=', 'id', $this->user->id])
            ->andWhere(['>', 'date_login', time() - 604800])
            ->orderBy(['login' => SORT_ASC])
            ->all();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.store.send.title'));
        return $this->render('send', [
            'model' => $model,
            'user' => $user,
            'userArray' => ArrayHelper::map($userArray, 'id', 'login'),
        ]);
    }

    /**
     * @return string
     */
    public function actionHistory(): string
    {
        $user = $this->user;
        $query = Money::find()
            ->where(['user_id' => $user->id])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.store.history.title'));
        return $this->render('history', [
            'dataProvider' => $dataProvider,
            'user' => $user,
        ]);
    }

    /**
     * @param int $day
     * @return string|Response
     */
    public function actionVip(int $day)
    {
        $user = $this->user;
        if (!in_array($day, [15, 30, 60, 180, 365])) {
            $day = 15;
        }
        $priceArray = [
            15 => 2,
            30 => 3,
            60 => 5,
            180 => 10,
            365 => 15,
        ];
        $price = self::getStorePriceWithDiscount($priceArray[$day]);

        if ($user->money < $price) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.store.vip.error-money'));
            return $this->redirect(['store/index']);
        }

        if (Yii::$app->request->get('ok')) {
            if ($user->date_vip < time()) {
                $dateVip = time() + $day * 60 * 60 * 24;
            } else {
                $dateVip = $user->date_vip + $day * 60 * 60 * 24;
            }

            try {
                Money::log([
                    'money_text_id' => MoneyText::OUTCOME_VIP,
                    'user_id' => $user->id,
                    'value' => -$price,
                    'value_after' => $user->money - $price,
                    'value_before' => $user->money,
                ]);

                $user->date_vip = $dateVip;
                $user->money -= $price;
                $user->save(true, ['date_vip', 'money']);
            } catch (Exception $e) {
                ErrorHelper::log($e);

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.store.vip.error-exception'));
                return $this->redirect(['index']);
            }

            $this->setSuccessFlash(Yii::t('frontend', 'controllers.store.vip.success'));
            return $this->redirect(['index']);
        }

        $message = Yii::t('frontend', 'controllers.store.vip.message', [
            'day' => $day,
            'price' => $price,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.store.vip.title'));
        return $this->render('vip', [
            'day' => $day,
            'message' => $message,
            'user' => $user,
        ]);
    }

    /**
     * @param float $price
     * @return float
     */
    public static function getStorePriceWithDiscount(float $price): float
    {
        return round($price * (1 - self::getStoreDiscount() / 100), 2);
    }

    /**
     * @return int
     */
    public static function getStoreDiscount(): int
    {
        try {
            $now = (new DateTime())->getTimestamp();
            $discountDates = self::getDiscountDates();

            foreach ($discountDates as $discountDate) {
                if ($now >= $discountDate[0] && $now <= $discountDate[1]) {
                    return 20;
                }
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        return 0;
    }

    /**
     * @return string|Response
     */
    public function actionFinance()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $user = $this->user;

        $price = self::getStorePriceWithDiscount(5 * $user->getStoreCoefficient());

        if ($user->money < $price) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.store.finance.error-money'));
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                Money::log([
                    'money_text_id' => MoneyText::OUTCOME_TEAM_FINANCE,
                    'user_id' => $user->id,
                    'value' => -$price,
                    'value_after' => $user->money - $price,
                    'value_before' => $user->money,
                ]);

                $user->money -= $price;
                $user->save(true, ['money']);

                $teamMoney = 1000000;

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_PRIZE_VIP,
                    'team_id' => $this->myTeam->id,
                    'value' => $teamMoney,
                    'value_after' => $this->myTeam->finance + $teamMoney,
                    'value_before' => $this->myTeam->finance,
                ]);

                $this->myTeam->finance += $teamMoney;
                $this->myTeam->save(true, ['finance']);
            } catch (Exception $e) {
                ErrorHelper::log($e);

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.store.finance.error-exception'));
                return $this->redirect(['index']);
            }

            $this->setSuccessFlash(Yii::t('frontend', 'controllers.store.finance.success'));
            return $this->redirect(['index']);
        }

        $message = Yii::t('frontend', 'controllers.store.finance.message', [
            'value' => FormatHelper::asCurrency(1000000),
            'price' => $price,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.store.finance.title'));
        return $this->render('finance', [
            'message' => $message,
            'user' => $user,
        ]);
    }
}
