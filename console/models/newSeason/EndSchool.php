<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Building;
use common\models\db\NameCountry;
use common\models\db\Physical;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\PlayerSpecial;
use common\models\db\School;
use common\models\db\Scout;
use common\models\db\Season;
use common\models\db\Style;
use common\models\db\SurnameCountry;
use Exception;

/**
 * Class EndSchool
 * @package console\models\newSeason
 */
class EndSchool
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        School::updateAll(['day' => 0], ['and', ['>', 'day', 0], ['ready' => null]]);

        $seasonId = Season::getCurrentSeason();

        $schoolArray = School::find()
            ->with(['team.baseSchool', 'team.stadium.city', 'team.baseMedical', 'team.buildingBase', 'team.baseScout'])
            ->where(['<=', 'day', 0])
            ->andWhere(['ready' => null])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($schoolArray as $school) {
            /**
             * @var School $school
             */
            $specialId = $school->special_id;
            $styleId = $school->style_id;
            $withSpecial = $school->team->baseSchool->with_special;
            $withStyle = $school->team->baseSchool->with_style;

            if ($school->is_with_special || $school->is_with_style) {
                if ($school->is_with_special) {
                    $check = School::find()
                        ->where([
                            'team_id' => $school->team_id,
                            'is_with_special' => true,
                            'season_id' => $seasonId,
                        ])
                        ->andWhere(['not', ['ready' => null]])
                        ->count();

                    if ($check >= $withSpecial) {
                        $specialId = 0;
                        $withSpecial = 0;
                    }
                } else {
                    $specialId = 0;
                    $withSpecial = 0;
                }

                if ($school->is_with_style) {
                    $check = School::find()
                        ->where([
                            'team_id' => $school->team_id,
                            'is_with_style' => 1,
                            'season_id' => $seasonId,
                        ])
                        ->andWhere(['not', ['ready' => null]])
                        ->count();

                    if ($check >= $withStyle) {
                        $styleId = Style::getRandStyleId();
                        $withStyle = 0;
                    }
                } else {
                    $styleId = Style::getRandStyleId();
                    $withStyle = 0;
                }
            } else {
                $specialId = 0;
                $withSpecial = 0;
                $styleId = Style::getRandStyleId();
                $withStyle = 0;
            }

            $player = new Player();
            $player->age = 17;
            $player->country_id = $school->team->stadium->city->country_id;
            $player->name_id = NameCountry::getRandNameId($school->team->stadium->city->country_id);
            $player->physical_id = Physical::getRandPhysicalId();
            $player->power_nominal = $school->team->baseSchool->power;
            $player->school_team_id = $school->team_id;
            $player->style_id = $styleId;
            $player->surname_id = SurnameCountry::getRandFreeSurnameId(
                $school->team_id,
                $school->team->stadium->city->country_id
            );
            $player->team_id = $school->team_id;
            $player->tire = $school->team->baseMedical->tire;
            if ($school->team->buildingBase && in_array($school->team->buildingBase->building_id, [Building::BASE, Building::MEDICAL], true)) {
                $player->tire = Player::TIRE_DEFAULT;
            }
            $player->training_ability = random_int(1, 5);
            $player->save();

            $playerPosition = new PlayerPosition();
            $playerPosition->player_id = $player->id;
            $playerPosition->position_id = $school->position_id;
            $playerPosition->save();

            if ($specialId) {
                $playerSpecial = new PlayerSpecial();
                $playerSpecial->level = 1;
                $playerSpecial->player_id = $player->id;
                $playerSpecial->special_id = $specialId;
                $playerSpecial->save();
            }

            if ($school->team->baseScout->level >= 5) {
                for ($i = 0; $i < 3; $i++) {
                    $scout = new Scout();
                    $scout->is_school = true;
                    $scout->percent = 100;
                    $scout->player_id = $player->id;
                    $scout->ready = time();
                    $scout->is_style = true;
                    $scout->team_id = $school->team_id;
                    $scout->save();
                }
            }

            if ($withSpecial) {
                $withSpecial = true;
            }
            if ($withStyle) {
                $withStyle = true;
            }

            $school->ready = time();
            $school->season_id = $seasonId;
            $school->is_with_special_request = $school->is_with_special;
            $school->is_with_special = $withSpecial;
            $school->is_with_style_request = $school->is_with_style;
            $school->is_with_style = $withStyle;
            $school->save();
        }
    }
}