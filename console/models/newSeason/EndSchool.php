<?php

namespace console\models\newSeason;

use common\models\NameCountry;
use common\models\Player;
use common\models\PlayerSpecial;
use common\models\School;
use common\models\Scout;
use common\models\Season;
use common\models\Style;
use common\models\SurnameCountry;
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
    public function execute()
    {
        School::updateAll(['school_day' => 0], ['and', ['>', 'school_day', 0], ['school_ready' => 0]]);

        $seasonId = Season::getCurrentSeason();

        $schoolArray = School::find()
            ->with(['team', 'team.baseSchool', 'team.baseScout', 'team.baseMedical', 'team.stadium.city'])
            ->where(['<=', 'school_day', 0])
            ->andWhere(['school_ready' => 0])
            ->orderBy(['school_id' => SORT_ASC])
            ->each();
        foreach ($schoolArray as $school) {
            /**
             * @var School $school
             */
            $specialId = $school->school_special_id;
            $styleId = $school->school_style_id;
            $withSpecial = $school->team->baseSchool->base_school_with_special;
            $withStyle = $school->team->baseSchool->base_school_with_style;

            if ($school->school_with_special || $school->school_with_style) {
                if ($school->school_with_special) {
                    $check = School::find()
                        ->where([
                            'school_team_id' => $school->school_team_id,
                            'school_with_special' => 1,
                            'school_season_id' => $seasonId,
                        ])
                        ->andWhere(['!=', 'school_ready', 0])
                        ->count();

                    if ($check >= $withSpecial) {
                        $specialId = 0;
                        $withSpecial = 0;
                    }
                } else {
                    $specialId = 0;
                    $withSpecial = 0;
                }

                if ($school->school_with_style) {
                    $check = School::find()
                        ->where([
                            'school_team_id' => $school->school_team_id,
                            'school_with_style' => 1,
                            'school_season_id' => $seasonId,
                        ])
                        ->andWhere(['!=', 'school_ready', 0])
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
            $player->player_country_id = $school->team->stadium->city->city_country_id;
            $player->player_name_id = NameCountry::getRandNameId($school->team->stadium->city->city_country_id);
            $player->player_position_id = $school->school_position_id;
            $player->player_power_nominal = $school->team->baseSchool->base_school_power;
            $player->player_style_id = $styleId;
            $player->player_surname_id = SurnameCountry::getRandFreeSurnameId(
                $school->school_team_id,
                $school->team->stadium->city->city_country_id
            );
            $player->player_team_id = $school->school_team_id;
            $player->player_tire = $school->team->baseMedical->base_medical_tire;
            $player->player_training_ability = rand(1, 5);
            $player->save();

            if ($specialId) {
                $playerSpecial = new PlayerSpecial();
                $playerSpecial->player_special_level = 1;
                $playerSpecial->player_special_player_id = $player->player_id;
                $playerSpecial->player_special_special_id = $specialId;
                $playerSpecial->save();
            }

            if ($school->team->baseScout->base_scout_base_level >= 5) {
                for ($i = 0; $i < 2; $i++) {
                    $scout = new Scout();
                    $scout->scout_is_school = 1;
                    $scout->scout_percent = 100;
                    $scout->scout_player_id = $player->player_id;
                    $scout->scout_ready = time();
                    $scout->scout_style = 1;
                    $scout->scout_team_id = $school->school_team_id;
                    $scout->save();
                }
            }

            if ($withSpecial) {
                $withSpecial = 1;
            }
            if ($withStyle) {
                $withStyle = 1;
            }

            $school->school_ready = time();
            $school->school_season_id = $seasonId;
            $school->school_with_special_request = $school->school_with_special;
            $school->school_with_special = $withSpecial;
            $school->school_with_style_request = $school->school_with_style;
            $school->school_with_style = $withStyle;
            $school->save();
        }
    }
}