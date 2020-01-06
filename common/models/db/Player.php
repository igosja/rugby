<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Exception;
use frontend\components\AbstractController;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Player
 * @package common\models\db
 *
 * @property int $player_id
 * @property int $player_age
 * @property int $player_country_id
 * @property int $player_date_no_action
 * @property int $player_game_row
 * @property int $player_game_row_old
 * @property int $player_injury
 * @property int $player_injury_day
 * @property int $player_loan_day
 * @property int $player_loan_team_id
 * @property int $player_mood_id
 * @property int $player_name_id
 * @property int $player_national_id
 * @property int $player_national_squad_id
 * @property int $player_no_deal
 * @property int $player_order
 * @property int $player_physical_id
 * @property int $player_position_id
 * @property int $player_power_nominal
 * @property int $player_power_nominal_s
 * @property int $player_power_old
 * @property int $player_power_real
 * @property int $player_price
 * @property int $player_rookie
 * @property int $player_salary
 * @property int $player_school_id
 * @property int $player_squad_id
 * @property int $player_style_id
 * @property int $player_surname_id
 * @property int $player_team_id
 * @property int $player_tire
 * @property int $player_training_ability
 *
 * @property Country $country
 * @property Loan $loan
 * @property Team $loanTeam
 * @property Name $name
 * @property National $national
 * @property Physical $physical
 * @property PlayerPosition[] $playerPositions
 * @property PlayerSpecial[] $playerSpecials
 * @property Team $schoolTeam
 * @property Scout $scout
 * @property Scout[] $scouts
 * @property Squad $squad
 * @property Squad $squadNational
 * @property StatisticPlayer[] $statisticPlayer
 * @property Style $style
 * @property Surname $surname
 * @property Team $team
 * @property Training $training
 * @property Transfer $transfer
 */
class Player extends AbstractActiveRecord
{
    public const AGE_READY_FOR_PENSION = 34;
    public const START_NUMBER_OF_PLAYERS = 30;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%player}}';
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $physical = Physical::getRandPhysical();

            if (!$this->player_age) {
                $this->player_age = 17;
            }
            if (!$this->player_name_id) {
                $this->player_name_id = NameCountry::getRandNameId($this->player_country_id);
            }
            if (!$this->player_power_nominal) {
                $this->player_power_nominal = $this->player_age * 2;
            }
            if (!$this->player_style_id) {
                $this->player_style_id = Style::getRandStyleId();
            }
            if (!isset($this->player_tire)) {
                $this->player_tire = 50;
            }
            if ($this->player_team_id) {
                $this->player_school_id = $this->player_team_id;
            }

            $this->player_game_row = -1;
            $this->player_game_row_old = -1;
            $this->player_squad_id = 1;
            $this->player_national_squad_id = 1;
            $this->player_physical_id = $physical->physical_id;
            $this->player_power_nominal_s = $this->player_power_nominal;
            $this->player_power_old = $this->player_power_nominal;
            if ($this->player_team_id) {
                $this->player_surname_id = SurnameCountry::getRandFreeSurnameId($this->player_team_id, $this->player_country_id);
            } else {
                $this->player_surname_id = SurnameCountry::getRandSurnameId($this->player_country_id);
            }
            $this->player_training_ability = random_int(1, 5);
            $this->player_power_real = $this->countRealPower($physical);
            $this->player_price = $this->countPrice();
            $this->player_salary = $this->countSalary();
        }
        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes): bool
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $playerPosition = new PlayerPosition();
            $playerPosition->player_position_player_id = $this->player_id;
            $playerPosition->player_position_position_id = $this->player_position_id;
            $playerPosition->save();

            History::log([
                'history_history_text_id' => HistoryText::PLAYER_FROM_SCHOOL,
                'history_player_id' => $this->player_id,
                'history_team_id' => $this->player_team_id
            ]);
        }
        return true;
    }

    /**
     * @return int
     */
    public function countPrice(): int
    {
        return round(((150 - (28 - $this->player_age)) ** 2) * $this->player_power_nominal);
    }

    /**
     * @return float|int
     */
    public function countSalary(): int
    {
        return round($this->player_price / 999);
    }

    /**
     * @param Physical|null $physical
     * @return float|int
     */
    public function countRealPower(Physical $physical = null)
    {
        if (!$physical) {
            $physical = $this->physical;
        }
        return $this->player_power_nominal * $this->player_tire / 100 * $physical->physical_value / 100;
    }

    /**
     * @param array $options
     * @return string
     */
    public function playerLink(array $options = []): string
    {
        return Html::a(
            $this->name->name_name . ' ' . $this->surname->surname_name,
            ['player/view', 'id' => $this->player_id],
            $options
        );
    }

    /**
     * @return string
     */
    public function powerNominal(): string
    {
        $class = '';
        if ($this->player_power_nominal > $this->player_power_old) {
            $class = 'font-green';
        } elseif ($this->player_power_nominal < $this->player_power_old) {
            $class = 'font-red';
        }
        return Html::tag('span', $this->player_power_nominal, ['class' => $class]);
    }

    /**
     * @return string
     */
    public function playerTire(): string
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $realTire = $this->player_tire . '%';

        if (!$controller->myTeam) {
            return '?';
        }

        if ($this->myPlayer()) {
            return $realTire;
        }

        if ($this->myNationalPlayer()) {
            return $realTire;
        }

        if ($controller->myTeam->baseScout->canSeeOpponentTire()) {
            return $realTire;
        }

        if (!$this->loan && !$this->transfer) {
            return '?';
        }

        if ($controller->myTeam->baseScout->canSeeDealTire()) {
            return $realTire;
        }

        return '?';
    }

    /**
     * @return string
     */
    public function playerGameRow(): string
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $realGameRow = $this->player_game_row;

        if (!$controller->myTeam) {
            return '?';
        }

        if ($this->myPlayer()) {
            return $realGameRow;
        }

        if ($this->myNationalPlayer()) {
            return $realGameRow;
        }

        if ($controller->myTeam->baseScout->canSeeOpponentGameRow()) {
            return $realGameRow;
        }

        if (!$this->loan && !$this->transfer) {
            return '?';
        }

        if ($controller->myTeam->baseScout->canSeeDealGameRow()) {
            return $realGameRow;
        }

        return '?';
    }

    /**
     * @return string
     */
    public function playerPhysical(): string
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $realPhysical = $this->physical->image();

        if (!$controller->myTeam) {
            return '?';
        }

        if ($this->myPlayer()) {
            return $realPhysical;
        }

        if ($this->myNationalPlayer()) {
            return $realPhysical;
        }

        if ($controller->myTeam->baseScout->canSeeOpponentPhysical()) {
            return $realPhysical;
        }

        if (!$this->loan && !$this->transfer) {
            return '?';
        }

        if ($controller->myTeam->baseScout->canSeeDealPhysical()) {
            return $realPhysical;
        }

        return '?';
    }

    /**
     * @return bool
     */
    public function myPlayer(): bool
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        if (!$controller->myTeam) {
            return false;
        }
        if ($controller->myTeamOrVice->team_id !== $this->player_team_id) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function myNationalPlayer(): bool
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        if (!$controller->myNational) {
            return false;
        }
        if ($controller->myNational->national_id !== $this->player_national_id) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function playerName(): string
    {
        return $this->name->name_name . ' ' . $this->surname->surname_name;
    }

    /**
     * @return string
     */
    public function position(): string
    {
        $result = [];
        foreach ($this->playerPositions as $position) {
            $result[] = Html::tag(
                'span',
                $position->position->position_name,
                ['title' => $position->position->position_text]
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
        foreach ($this->playerSpecials as $special) {
            $result[] = $special->special->special_name . $special->player_special_level;
        }
        return implode(' ', $result);
    }

    /**
     * @return string
     */
    public function iconPension(): string
    {
        $result = ' ';
        if (self::AGE_READY_FOR_PENSION === $this->player_age) {
            $result .= Html::tag('i', '', [
                'class' => ['fa', 'fa-fa-home'],
                'title' => 'Заканчивает карьеру в конце текущего сезона',
            ]);
        }
        return $result;
    }

    /**
     * @param boolean $showOnlyIfStudied
     * @return string
     */
    public function iconStyle(bool $showOnlyIfStudied = false): string
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $myTeam = $controller->myTeam;

        if (!$myTeam) {
            if (!$showOnlyIfStudied) {
                $styleArray = Style::find()
                    ->select(['style_id', 'style_name'])
                    ->where(['!=', 'style_id', Style::NORMAL])
                    ->orderBy(['style_id' => SORT_ASC])
                    ->all();
            } else {
                $styleArray = [];
            }
        } else {
            $countScout = count($this->scouts);
            if (2 === $countScout) {
                $styleArray = Style::find()
                    ->select(['style_id', 'style_name'])
                    ->where(['style_id' => $this->player_style_id])
                    ->orderBy(['style_id' => SORT_ASC])
                    ->all();
            } elseif (!$showOnlyIfStudied) {
                $in = [$this->player_style_id];
                for ($i = 1; $i < 3 - $countScout; $i++) {
                    $styleToIn = Style::getRandStyleId($in);
                    $in[] = $styleToIn;
                }
                $styleArray = Style::find()
                    ->select(['style_id', 'style_name'])
                    ->where(['style_id' => $in])
                    ->orderBy(['style_id' => SORT_ASC])
                    ->limit(3 - $countScout)
                    ->all();
            } else {
                $styleArray = [];
            }
        }

        $result = [];
        foreach ($styleArray as $style) {
            /**
             * @var Style $style
             */
            $result[] = Html::img(
                '/img/style/' . $style->style_id . '.png',
                [
                    'alt' => $style->style_name,
                    'title' => ucfirst($style->style_name),
                ]
            );
        }

        return implode(' ', $result);
    }

    /**
     * @return int
     */
    public function countScout(): int
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $myTeam = $controller->myTeam;

        if ($myTeam) {
            return Scout::find()
                ->where(['scout_player_id' => $this->player_id, 'scout_team_id' => $myTeam->team_id])
                ->andWhere(['!=', 'scout_ready', 0])
                ->count();
        }

        return 0;
    }

    /**
     * @return string
     */
    public function iconTraining(): string
    {
        $result = ' ';
        if ($this->training) {
            $result .= Html::tag('i', '', [
                'class' => ['fa', 'fa-level-up'],
                'title' => 'На тренировке',
            ]);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconScout(): string
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $myTeam = $controller->myTeam;

        $scout = null;
        if ($myTeam) {
            $scout = $this->scout;
        }

        $result = ' ';
        if ($scout) {
            $result .= Html::tag('i', '', [
                'class' => ['fa', 'fa-search'],
                'title' => 'Изучается в скаутцентре вашей команды',
            ]);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconLoan(): string
    {
        $result = ' ';
        if ($this->player_loan_day) {
            $result .= Html::tag('span', '(' . $this->player_loan_day . ')', ['title' => 'В аренде']);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconInjury(): string
    {
        $result = ' ';
        if ($this->player_injury) {
            $result .= Html::tag('i', '', [
                'class' => ['fa', 'fa-ambulance'],
                'title' => 'Травмирован на ' . $this->player_injury_day . ' дн.',
            ]);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconDeal(): string
    {
        $result = ' ';
        if ($this->loan || $this->transfer) {
            $result .= Html::tag('i', '', ['class' => ['fa', 'fa-usd'], 'title' => 'Выставлен на трансфер/аренду']);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconNational(): string
    {
        $result = ' ';
        if ($this->player_national_id) {
            $result .= Html::tag('i', '', [
                'class' => ['fa', 'fa-flag'],
                'title' => 'Игрок ' . $this->national->nationalType->national_type_name . ' сборной',
            ]);
        }
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['country_id' => 'player_country_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getLoan(): ActiveQuery
    {
        return $this->hasOne(Loan::class, ['loan_player_id' => 'player_id'])->andWhere(['loan_ready' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getLoanTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'player_loan_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getName(): ActiveQuery
    {
        return $this->hasOne(Name::class, ['name_id' => 'player_name_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['national_id' => 'player_national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhysical(): ActiveQuery
    {
        return $this->hasOne(Physical::class, ['physical_id' => 'player_physical_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayerPositions(): ActiveQuery
    {
        return $this->hasMany(PlayerPosition::class, ['player_position_player_id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayerSpecials(): ActiveQuery
    {
        return $this->hasMany(PlayerSpecial::class, ['player_special_player_id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSchoolTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'player_school_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getScout(): ActiveQuery
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $team = $controller->myTeam;
        $teamId = null;
        if ($team) {
            $teamId = $team->team_id;
        }
        return $this
            ->hasOne(Scout::class, ['scout_player_id' => 'player_id'])
            ->andWhere(['scout_ready' => 0])
            ->andFilterWhere(['scout_team_id' => $teamId]);
    }

    /**
     * @return ActiveQuery
     */
    public function getScouts(): ActiveQuery
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        $team = $controller->myTeam;
        $teamId = null;
        if ($team) {
            $teamId = $team->team_id;
        }
        return $this
            ->hasMany(Scout::class, ['scout_player_id' => 'player_id'])
            ->andWhere(['!=', 'scout_ready', 0])
            ->andFilterWhere(['scout_team_id' => $teamId]);
    }

    /**
     * @return ActiveQuery
     */
    public function getSquad(): ActiveQuery
    {
        return $this->hasOne(Squad::class, ['squad_id' => 'player_squad_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSquadNational(): ActiveQuery
    {
        return $this->hasOne(Squad::class, ['squad_id' => 'player_national_squad_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatisticPlayer(): ActiveQuery
    {
        return $this
            ->hasMany(StatisticPlayer::class, ['statistic_player_player_id' => 'player_id'])
            ->andWhere(['statistic_player_season_id' => Season::getCurrentSeason()]);
    }

    /**
     * @return ActiveQuery
     */
    public function getStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['style_id' => 'player_style_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSurname(): ActiveQuery
    {
        return $this->hasOne(Surname::class, ['surname_id' => 'player_surname_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'player_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTraining(): ActiveQuery
    {
        return $this->hasOne(Training::class, ['training_player_id' => 'player_id'])->andWhere(['training_ready' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['transfer_player_id' => 'player_id'])->andWhere(['transfer_ready' => 0]);
    }
}
