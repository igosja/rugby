<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class PreNews
 * @package common\models\db
 *
 * @property int $pre_news_id
 * @property string $pre_news_new
 * @property string $pre_news_error
 */
class PreNews extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%pre_news}}';
    }
}
