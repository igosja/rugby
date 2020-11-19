<?php

// TODO refactor

namespace frontend\controllers;

use Exception;

/**
 * Class SiteController
 * @package frontend\controllers
 *
 * Среднее:
 * 24 очка за игру на одну команду
 * 2.8 попытки за игру на одну команду
 * 2.2 забитых штрафных за игру на одну команду
 * 0.005 дроп-голов за игру на одну команду
 * 1.68 реализаций за игру на одну команду
 * 0.4 желтых за игру на одну команду
 * 0.02 красных за игру на одну команду
 * 60% реализаций
 */
class TestController extends AbstractController
{
    private const COEFFICIENT_FORWARD_ATTACK = 4;
    private const COEFFICIENT_FORWARD_DEFENCE = 10;
    private const COEFFICIENT_HALF_ATTACK = 7;
    private const COEFFICIENT_HALF_DEFENCE = 6;
    private const COEFFICIENT_BACK_ATTACK = 10;
    private const COEFFICIENT_BACK_DEFENCE = 8;
    private const COEFFICIENT_PENALTY_ATTACK = 4;
    private const COEFFICIENT_PENALTY_DEFENCE = 6;
    private const COEFFICIENT_DROP_ATTACK = 1;
    private const COEFFICIENT_DROP_DEFENCE = 225;
    private const GAMES = 135 * 2 * 100;

    /**
     * @throws Exception
     */
    public function actionIndex(): void
    {
        $result = [
            'win' => 0,
            'draw' => 0,
            'loose' => 0,
            'dry' => 0,
            'points' => 0,
            'try' => 0,
            'convention' => 0,
            'penalty' => 0,
            'drop' => 0,
            'yellow' => 0,
            'red' => 0,
        ];
        for ($game = 0; $game < self::GAMES; $game++) {
            $points = [
                'home' => 0,
                'guest' => 0,
                'try' => 0,
                'convention' => 0,
                'penalty' => 0,
                'drop' => 0,
            ];

            $homeBonus = 1;

            $homePlayer = 10 * $homeBonus;
            $back['home'] = $homePlayer * 5;
            $half['home'] = $homePlayer * 2;
            $forward['home'] = $homePlayer * 8;

            $guestPlayer = 10;
            $back['guest'] = $guestPlayer * 5;
            $half['guest'] = $guestPlayer * 2;
            $forward['guest'] = $guestPlayer * 8;
            for ($minute = 0; $minute < 80; $minute++) {
                if (0 === $minute % 2) {
                    $attack = 'home';
                    $defence = 'guest';
                } else {
                    $attack = 'guest';
                    $defence = 'home';
                }
                $backAttack = $back[$attack] * self::COEFFICIENT_BACK_ATTACK;
                $forwardDefence = $forward[$defence] * self::COEFFICIENT_FORWARD_DEFENCE;
                if (random_int(0, $backAttack) > random_int(0, $forwardDefence)) {
                    $halfAttack = $half[$attack] * self::COEFFICIENT_HALF_ATTACK;
                    $halfDefence = $half[$defence] * self::COEFFICIENT_HALF_DEFENCE;
                    if (random_int(0, $halfAttack) > random_int(0, $halfDefence)) {
                        $forwardAttack = $forward[$attack] * self::COEFFICIENT_FORWARD_ATTACK;
                        $backDefence = $back[$defence] * self::COEFFICIENT_BACK_DEFENCE;
                        if (random_int(0, $forwardAttack) > random_int(0, $backDefence)) {
                            $points[$attack] += 5;
                            $points['try']++;
                            if (random_int(0, 100) > 40) {
                                $points[$attack] += 2;
                                $points['convention']++;
                            }
                        } else {
                            $forwardAttack = $forward[$attack] * self::COEFFICIENT_PENALTY_ATTACK;
                            $backDefence = $back[$defence] * self::COEFFICIENT_PENALTY_DEFENCE;
                            if (random_int(0, $forwardAttack) > random_int(0, $backDefence)) {
                                $points[$attack] += 3;
                                $points['penalty']++;
                            } else {
                                $forwardAttack = $forward[$attack] * self::COEFFICIENT_DROP_ATTACK;
                                $backDefence = $back[$defence] * self::COEFFICIENT_DROP_DEFENCE;
                                if (random_int(0, $forwardAttack) > random_int(0, $backDefence)) {
                                    $points[$attack] += 3;
                                    $points['drop']++;
                                }
                            }
                        }
                    }
                }
            }
            if ($points['home'] > $points['guest']) {
                $result['win']++;
            } elseif ($points['home'] === $points['guest']) {
                $result['draw']++;
                if (0 === $points['home']) {
                    $result['dry']++;
                }
            } elseif ($points['home'] < $points['guest']) {
                $result['loose']++;
            }
            $yellow = 0;
            if (random_int(1, 5) <= 2) {
                $yellow++;
            }
            $red = 0;
            if (random_int(1, 50) <= 1) {
                $red++;
            }
            $result['points'] += $points['home'];
            $result['points'] += $points['guest'];
            $result['try'] += $points['try'];
            $result['convention'] += $points['convention'];
            $result['penalty'] += $points['penalty'];
            $result['drop'] += $points['drop'];
            $result['yellow'] += $yellow;
            $result['red'] += $red;
        }

//        $games = self::GAMES * 2;

//        $result['points'] /= $games;
//        $result['try'] /= $games;
//        $result['convention'] /= $games;
//        $result['penalty'] /= $games;

        print '<pre>';
        print_r($result);
        exit;
    }
}
