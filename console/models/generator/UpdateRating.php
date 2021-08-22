<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Federation;
use common\models\db\RatingFederation;
use common\models\db\RatingTeam;
use common\models\db\RatingType;
use common\models\db\RatingUser;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\User;
use Yii;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class UpdateRating
 * @package console\models\generator
 */
class UpdateRating
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        Yii::$app->db->createCommand()->truncateTable('rating_federation')->execute();
        Yii::$app->db->createCommand()->truncateTable('rating_team')->execute();
        Yii::$app->db->createCommand()->truncateTable('rating_user')->execute();

        $sql = "INSERT INTO `rating_federation` (`federation_id`)
                SELECT `federation`.`id`
                FROM `team`
                LEFT JOIN `stadium`
                ON `stadium_id`=`stadium`.`id`
                LEFT JOIN `city`
                ON `city_id`=`city`.`id`
                LEFT JOIN `country`
                ON `city`.`country_id`=`country`.`id`
                LEFT JOIN `federation`
                ON `country`.`id`=`federation`.`country_id`
                WHERE `team`.`id`!=0
                GROUP BY `federation`.`id`
                ORDER BY `federation`.`id`";
        Yii::$app->db->createCommand($sql)->execute();

        $teamInsertData = [];
        $teamInsertKeys = ['team_id'];
        $userInsertData = [];
        $userInsertKeys = ['user_id'];

        $ratingTypeArray = RatingType::find()
            ->orderBy(['rating_chapter_id' => SORT_ASC, 'id' => SORT_ASC])
            ->each();
        foreach ($ratingTypeArray as $ratingType) {
            /**
             * @var RatingType $ratingType
             */
            if (RatingType::TEAM_POWER === $ratingType->id) {
                $order = '`power_vs` DESC';
                $place = 'power_vs_place';
            } elseif (RatingType::TEAM_AGE === $ratingType->id) {
                $order = '`player_average_age`';
                $place = 'age_place';
            } elseif (RatingType::TEAM_STADIUM === $ratingType->id) {
                $order = '`price_stadium` DESC';
                $place = 'stadium_place';
            } elseif (RatingType::TEAM_VISITOR === $ratingType->id) {
                $order = '`visitor` DESC';
                $place = 'visitor_place';
            } elseif (RatingType::TEAM_BASE === $ratingType->id) {
                $order = '`base_id`+`base_medical_id`+`base_physical_id`+`base_school_id`+`base_scout_id`+`base_training_id` DESC';
                $place = 'base_place';
            } elseif (RatingType::TEAM_FINANCE === $ratingType->id) {
                $order = '`finance` DESC';
                $place = 'finance_place';
            } elseif (RatingType::TEAM_PRICE_BASE === $ratingType->id) {
                $order = '`price_base` DESC';
                $place = 'price_base_place';
            } elseif (RatingType::TEAM_PRICE_STADIUM === $ratingType->id) {
                $order = '`price_stadium` DESC';
                $place = 'price_stadium_place';
            } elseif (RatingType::TEAM_PLAYER === $ratingType->id) {
                $order = '`player_number` DESC';
                $place = 'player_place';
            } elseif (RatingType::TEAM_PRICE_TOTAL === $ratingType->id) {
                $order = '`price_total` DESC';
                $place = 'price_total_place';
            } elseif (RatingType::USER_RATING === $ratingType->id) {
                $order = '`rating` DESC';
                $place = 'rating_place';
            } elseif (RatingType::TEAM_SALARY === $ratingType->id) {
                $order = '`salary` DESC';
                $place = 'salary_place';
            } elseif (RatingType::FEDERATION_STADIUM === $ratingType->id) {
                $order = '`stadium_capacity` DESC';
                $place = 'stadium_place';
            } elseif (RatingType::FEDERATION_AUTO === $ratingType->id) {
                $order = '`auto`/`game`';
                $place = 'auto_place';
            } else {
                $place = 'league_place';
            }

            if (in_array($ratingType->id, [
                RatingType::TEAM_AGE,
                RatingType::TEAM_BASE,
                RatingType::TEAM_FINANCE,
                RatingType::TEAM_PLAYER,
                RatingType::TEAM_POWER,
                RatingType::TEAM_PRICE_BASE,
                RatingType::TEAM_PRICE_STADIUM,
                RatingType::TEAM_PRICE_TOTAL,
                RatingType::TEAM_SALARY,
                RatingType::TEAM_STADIUM,
                RatingType::TEAM_VISITOR,
            ], true)) {
                $teamInsertKeys[] = $place;

                $position = 1;
                $teamArray = Team::find()
                    ->where(['!=', 'id', 0])
                    ->orderBy(new Expression($order . ', `id` ASC'))
                    ->each();
                foreach ($teamArray as $team) {
                    /**
                     * @var Team $team
                     */
                    if (!array_key_exists($team->id, $teamInsertData)) {
                        $teamInsertData[$team->id] = [$team->id];
                    }
                    $teamInsertData[$team->id][] = $position;
                    $position++;
                }

                $place .= '_federation';
                $teamInsertKeys[] = $place;
                $federationArray = Federation::find()
                    ->joinWith(['country.cities.stadiums.team'], false)
                    ->where(['!=', 'team.id', 0])
                    ->groupBy(['federation.id'])
                    ->orderBy(['federation.id' => SORT_ASC])
                    ->each();
                foreach ($federationArray as $federation) {
                    /**
                     * @var Federation $federation
                     */
                    $position = 1;
                    $teamArray = Team::find()
                        ->joinWith(['stadium.city'], false)
                        ->where(['country_id' => $federation->country_id])
                        ->orderBy(new Expression($order . ', `id`'))
                        ->each();
                    foreach ($teamArray as $team) {
                        /**
                         * @var Team $team
                         */
                        $teamInsertData[$team->id][] = $position;
                        $position++;
                    }
                }
            } elseif (RatingType::USER_RATING === $ratingType->id) {
                $userInsertKeys[] = $place;
                $position = 1;
                $userArray = User::find()
                    ->joinWith(['teams'], false)
                    ->where(['not', ['team.id' => null]])
                    ->andWhere(['!=', 'user.id', 0])
                    ->groupBy(['user.id'])
                    ->orderBy(new Expression($order . ', `user`.`id` ASC'))
                    ->each();
                foreach ($userArray as $user) {
                    /**
                     * @var User $user
                     */
                    if (!isset($userInsertData[$user->id])) {
                        $userInsertData[$user->id] = [$user->id];
                    }
                    $userInsertData[$user->id][] = $position;
                    $position++;
                }
            } elseif (in_array($ratingType->id, [RatingType::FEDERATION_AUTO, RatingType::FEDERATION_STADIUM], true)) {
                $position = 1;
                $federationArray = Federation::find()
                    ->joinWith(['country.cities'], false)
                    ->where(['!=', 'city.id', 0])
                    ->groupBy(['federation.id'])
                    ->orderBy(new Expression($order . ', `federation`.`id`'))
                    ->each();
                foreach ($federationArray as $federation) {
                    /**
                     * @var Federation $federation
                     */
                    RatingFederation::updateAll([$place => $position], ['federation_id' => $federation->id]);
                    $position++;
                }
            } elseif (RatingType::FEDERATION_LEAGUE === $ratingType->id) {
                $position = 1;
                $sql = "SELECT `federation`.`id`
                        FROM `city`
                        LEFT JOIN `country`
                        ON `country_id`=`country`.`id`
                        LEFT JOIN `federation`
                        ON `country`.`id`=`federation`.`country_id`
                        LEFT JOIN 
                        (
                            SELECT SUM(`point`)/COUNT(`team_id`) AS `coeff_1`,
                                   `federation_id`
                            FROM `league_coefficient`
                            WHERE `season_id`=$seasonId
                            GROUP BY `federation_id`
                        ) AS `t1`
                        ON `federation`.`id`=`t1`.`federation_id`
                        LEFT JOIN 
                        (
                            SELECT SUM(`point`)/COUNT(`team_id`) AS `coeff_2`,
                                   `federation_id`
                            FROM `league_coefficient`
                            WHERE `season_id`=$seasonId-1
                            GROUP BY `federation_id`
                        ) AS `t2`
                        ON `federation`.`id`=`t2`.`federation_id`
                        LEFT JOIN 
                        (
                            SELECT SUM(`point`)/COUNT(`team_id`) AS `coeff_3`,
                                   `federation_id`
                            FROM `league_coefficient`
                            WHERE `season_id`=$seasonId-2
                            GROUP BY `federation_id`
                        ) AS `t3`
                        ON `federation`.`id`=`t3`.`federation_id`
                        LEFT JOIN 
                        (
                            SELECT SUM(`point`)/COUNT(`team_id`) AS `coeff_4`,
                                   `federation_id`
                            FROM `league_coefficient`
                            WHERE `season_id`=$seasonId-3
                            GROUP BY `federation_id`
                        ) AS `t4`
                        ON `federation`.`id`=`t4`.`federation_id`
                        LEFT JOIN 
                        (
                            SELECT SUM(`point`)/COUNT(`team_id`) AS `coeff_5`,
                                   `federation_id`
                            FROM `league_coefficient`
                            WHERE `season_id`=$seasonId-4
                            GROUP BY `federation_id`
                        ) AS `t5`
                        ON `federation`.`id`=`t5`.`federation_id`
                        WHERE `city`.`id`!=0
                        GROUP BY `federation`.`id`
                        ORDER BY IFNULL(`coeff_1`, 0)+IFNULL(`coeff_2`, 0)+IFNULL(`coeff_3`, 0)+IFNULL(`coeff_4`, 0)+IFNULL(`coeff_5`, 0) DESC, `federation`.`id`";
                $federationArray = Yii::$app->db->createCommand($sql)->queryAll();

                foreach ($federationArray as $federation) {
                    RatingFederation::updateAll(
                        [$place => $position],
                        ['federation_id' => $federation['id']]
                    );
                    $position++;
                }
            }
        }

        Yii::$app->db->createCommand()->batchInsert(
            RatingTeam::tableName(),
            $teamInsertKeys,
            $teamInsertData
        )->execute();

        Yii::$app->db->createCommand()->batchInsert(
            RatingUser::tableName(),
            $userInsertKeys,
            $userInsertData
        )->execute();
    }
}
