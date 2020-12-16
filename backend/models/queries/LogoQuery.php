<?php

// TODO refactor

namespace backend\models\queries;

use common\models\db\Logo;

/**
 * Class LogoQuery
 * @package backend\models\queries
 */
class LogoQuery
{
    /**
     * @return int
     */
    public static function countNewTeamLogo(): int
    {
        return Logo::find()
            ->andWhere(['not', ['team_id' => null]])
            ->count();
    }

    /**
     * @return int
     */
    public static function countNewUserPhoto(): int
    {
        return Logo::find()
            ->andWhere(['not', ['user_id' => null]])
            ->count();
    }
}
