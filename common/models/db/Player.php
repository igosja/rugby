<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use frontend\controllers\AbstractController;
use rmrevin\yii\fontawesome\FAS;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Player
 * @package common\models\db
 *
 * @property int $id
 * @property int $age
 * @property int $country_id
 * @property int $date_no_action
 * @property int $game_row
 * @property int $game_row_old
 * @property int $injury_day
 * @property int $is_injury
 * @property int $is_no_deal
 * @property int $loan_day
 * @property int $loan_team_id
 * @property int $mood_id
 * @property int $name_id
 * @property int $national_id
 * @property int $national_squad_id
 * @property int $order
 * @property int $physical_id
 * @property int $power_nominal
 * @property int $power_nominal_s
 * @property int $power_old
 * @property int $power_real
 * @property int $price
 * @property int $salary
 * @property int $school_team_id
 * @property int $squad_id
 * @property int $style_id
 * @property int $surname_id
 * @property int $team_id
 * @property int $tire
 * @property int $training_ability
 *
 * @property-read Country $country
 * @property-read Loan $loan
 * @property-read Team $loanTeam
 * @property-read Mood $mood
 * @property-read Name $name
 * @property-read National $national
 * @property-read Squad $nationalSquad
 * @property-read Physical $physical
 * @property-read PlayerPosition[] $playerPositions
 * @property-read PlayerSpecial[] $playerSpecials
 * @property-read Team $schoolTeam
 * @property-read Squad $squad
 * @property-read Style $style
 * @property-read Surname $surname
 * @property-read Team $team
 * @property-read Transfer $transfer
 */
class Player extends AbstractActiveRecord
{
    public const AGE_READY_FOR_PENSION = 35;
    public const START_NUMBER_OF_PLAYERS = 30;
    public const TIRE_DEFAULT = 50;
    public const TIRE_MAX_FOR_LINEUP = 90;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%player}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'age',
                    'country_id',
                    'name_id',
                    'physical_id',
                    'school_team_id',
                    'style_id',
                    'surname_id',
                    'team_id',
                ],
                'required'
            ],
            [['is_injury', 'is_no_deal'], 'boolean'],
            [['loan_day'], 'integer', 'min' => 0, 'max' => 9],
            [
                ['mood_id', 'national_squad_id', 'squad_id', 'style_id', 'training_ability'],
                'integer',
                'min' => 1,
                'max' => 9
            ],
            [['age', 'physical_id', 'tire'], 'integer', 'min' => 1, 'max' => 99],
            [['game_row', 'game_row_old'], 'integer', 'min' => -99, 'max' => 99],
            [
                ['country_id', 'national_id', 'order', 'power_nominal', 'power_nominal_s', 'power_old', 'power_real'],
                'integer',
                'min' => 0,
                'max' => 999
            ],
            [
                ['date_no_action', 'name_id', 'price', 'salary', 'school_team_id', 'surname_id', 'team_id'],
                'integer',
                'min' => 0
            ],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['loan_team_id'], 'exist', 'targetRelation' => 'loanTeam'],
            [['mood_id'], 'exist', 'targetRelation' => 'mood'],
            [['name_id'], 'exist', 'targetRelation' => 'name'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['national_squad_id'], 'exist', 'targetRelation' => 'nationalSquad'],
            [['physical_id'], 'exist', 'targetRelation' => 'physical'],
            [['school_team_id'], 'exist', 'targetRelation' => 'schoolTeam'],
            [['squad_id'], 'exist', 'targetRelation' => 'squad'],
            [['style_id'], 'exist', 'targetRelation' => 'style'],
            [['surname_id'], 'exist', 'targetRelation' => 'surname'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return string
     */
    public function trainingPositionDropDownList(): string
    {
        if (2 === count($this->playerPositions)) {
            return '';
        }

        $positionArray = Position::find()
            ->andWhere([
                'not',
                [
                    'id' => PlayerPosition::find()
                        ->select(['position_id'])
                        ->where(['player_id' => $this->id])
                ]
            ])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return Html::dropDownList(
            'position[' . $this->id . ']',
            null,
            ArrayHelper::map($positionArray, 'id', 'name'),
            ['class' => 'form-control form-small', 'prompt' => '-']
        );
    }

    /**
     * @return string
     */
    public function trainingSpecialDropDownList(): string
    {
        $playerSpecial = PlayerSpecial::find()
            ->where(['level' => Special::MAX_LEVEL, 'player_id' => $this->id])
            ->count();
        if (Special::MAX_SPECIALS === $playerSpecial) {
            return '';
        }

        $specialId = null;
        $playerSpecial = PlayerSpecial::find()
            ->where(['player_id' => $this->id])
            ->count();
        if (Special::MAX_SPECIALS === $playerSpecial) {
            $specialId = PlayerSpecial::find()
                ->select(['special_id'])
                ->where(['player_id' => $this->id])
                ->andWhere(['<', 'level', Special::MAX_LEVEL])
                ->column();
        }

        $specialArray = Special::find()
            ->andWhere([
                'not',
                [
                    'id' => PlayerSpecial::find()
                        ->select(['special_id'])
                        ->where([
                            'level' => Special::MAX_LEVEL,
                            'player_id' => $this->id,
                        ])
                ]
            ])
            ->andFilterWhere(['id' => $specialId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return Html::dropDownList(
            'special[' . $this->id . ']',
            null,
            ArrayHelper::map($specialArray, 'id', 'name'),
            ['class' => 'form-control form-small', 'prompt' => '-']
        );
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function makeFree(): bool
    {
        History::log([
            'history_text_id' => HistoryText::PLAYER_FREE,
            'player_id' => $this->id,
            'team_id' => $this->team_id,
        ]);

        $this->loan_day = null;
        $this->loan_team_id = null;
        $this->national_squad_id = Squad::SQUAD_DEFAULT;
        $this->order = 0;
        $this->squad_id = Squad::SQUAD_DEFAULT;
        $this->team_id = 0;
        $this->save(true, [
            'loan_day',
            'loan_team_id',
            'national_squad_id',
            'order',
            'squad_id',
            'team_id',
        ]);

        return true;
    }

    /**
     * @return string
     */
    public function playerName(): string
    {
        return $this->name->name . ' ' . $this->surname->name;
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
        $realGameRow = $this->game_row;

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
        if ($controller->myTeamOrVice->id !== $this->team_id) {
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
        if ($controller->myNational->id !== $this->national_id) {
            return false;
        }
        return true;
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
        $realTire = $this->tire . '%';

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
    public function powerNominal(): string
    {
        $class = '';
        if ($this->power_nominal > $this->power_old) {
            $class = 'font-green';
        } elseif ($this->power_nominal < $this->power_old) {
            $class = 'font-red';
        }
        return '<span class="' . $class . '">' . $this->power_nominal . '</span>';
    }

    /**
     * @return string
     */
    public function iconInjury(): string
    {
        $result = '';
        if ($this->is_injury) {
            $result = ' ' . FAS::icon(FAS::_AMBULANCE, ['title' => 'Травмирован на ' . $this->injury_day . ' дн.']);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconDeal(): string
    {
        $result = '';
        if ($this->loan || $this->transfer) {
            $result = ' ' . FAS::icon(FAS::_BALANCE_SCALE, ['title' => 'Выставлен на трансфер/аренду']);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconNational(): string
    {
        $result = '';
        if ($this->national_id) {
            if (NationalType::MAIN === $this->national->national_type_id) {
                $text = 'национальной сборной';
            } else {
                $text = 'сборной ' . $this->national->nationalType->name;
            }
            $result = ' ' . FAS::icon(FAS::_FLAG, ['title' => 'Игрок ' . $text]);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconPension(): string
    {
        $result = '';
        if (self::AGE_READY_FOR_PENSION === $this->age) {
            $result = ' ' . FAS::icon(FAS::_HOME, ['title' => 'Заканчивает карьеру в конце текущего сезона']);
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
                    ->andWhere(['!=', 'id', Style::NORMAL])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
            } else {
                $styleArray = [];
            }
        } else {
            $countScout = Scout::find()
                ->andWhere(['player_id' => $this->id, 'team_id' => $myTeam->id])
                ->andWhere(['not', ['ready' => null]])
                ->count();

            if (2 === $countScout) {
                $styleArray = Style::find()
                    ->andWhere(['id' => $this->style_id])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
            } elseif (!$showOnlyIfStudied) {
                $in = [$this->style_id];
                for ($i = 1; $i < 4 - $countScout; $i++) {
                    $styleToIn = Style::getRandStyleId($in);
                    $in[] = $styleToIn;
                }
                $styleArray = Style::find()
                    ->where(['id' => $in])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(4 - $countScout)
                    ->all();
            } else {
                $styleArray = [];
            }
        }

        /**
         * @var Style[] $styleArray
         */
        $result = [];
        foreach ($styleArray as $item) {
            $result[] = Html::img(
                    '/img/style/' . $item->id . '.png',
                    [
                        'alt' => $item->name,
                        'title' => ucfirst($item->name),
                    ]
                ) . $item->id;
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
                ->andWhere(['player_id' => $this->id, 'team_id' => $myTeam->id])
                ->andWhere(['not', ['ready' => null]])
                ->count();
        }

        return 0;
    }

    /**
     * @return string
     */
    public function iconTraining(): string
    {
        $countTraining = Training::find()
            ->andWhere(['player_id' => $this->id, 'team_id' => $this->team_id, 'ready' => null])
            ->count();

        $result = '';
        if ($countTraining) {
            $result = ' ' . FAS::icon(FAS::_LEVEL_UP_ALT, ['title' => 'На тренировке']);
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

        if ($myTeam) {
            $countScout = Scout::find()
                ->andWhere(['player_id' => $this->id, 'team_id' => $myTeam->id, 'ready' => null])
                ->count();
        } else {
            $countScout = 0;
        }

        $result = '';
        if ($countScout) {
            $result = ' ' . FAS::icon(FAS::_SEARCH, ['title' => 'Изучается в скаутцентре вашей команды']);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconLoan(): string
    {
        $result = '';
        if ($this->loan_day) {
            $result = ' <span title="В аренде">(' . $this->loan_day . ')</i>';
        }
        return $result;
    }

    /**
     * @return string
     */
    public function position(): string
    {
        $result = [];
        foreach ($this->playerPositions as $playerPosition) {
            $result[] = Html::tag(
                'span',
                $playerPosition->position->name,
                ['title' => $playerPosition->position->text]
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
        foreach ($this->playerSpecials as $playerSpecial) {
            $result[] = Html::tag(
                'span',
                $playerSpecial->special->name . $playerSpecial->level,
                ['title' => $playerSpecial->special->text]
            );
        }
        return implode(' ', $result);
    }

    /**
     * @param array $options
     * @return string
     */
    public function getPlayerLink(array $options = []): string
    {
        return Html::a(
            $this->name->name . ' ' . $this->surname->name,
            ['/player/view', 'id' => $this->id],
            $options
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLoan(): ActiveQuery
    {
        return $this->hasOne(Loan::class, ['player_id' => 'id'])->andWhere(['ready' => null]);
    }

    /**
     * @return ActiveQuery
     */
    public function getLoanTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'loan_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMood(): ActiveQuery
    {
        return $this->hasOne(Mood::class, ['id' => 'mood_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getName(): ActiveQuery
    {
        return $this->hasOne(Name::class, ['id' => 'name_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalSquad(): ActiveQuery
    {
        return $this->hasOne(Squad::class, ['id' => 'national_squad_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhysical(): ActiveQuery
    {
        return $this->hasOne(Physical::class, ['id' => 'physical_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayerPositions(): ActiveQuery
    {
        return $this->hasMany(PlayerPosition::class, ['player_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayerSpecials(): ActiveQuery
    {
        return $this->hasMany(PlayerSpecial::class, ['player_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSchoolTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'school_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSquad(): ActiveQuery
    {
        return $this->hasOne(Squad::class, ['id' => 'squad_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'style_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSurname(): ActiveQuery
    {
        return $this->hasOne(Surname::class, ['id' => 'surname_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['player_id' => 'id'])->andWhere(['ready' => null]);
    }
}
