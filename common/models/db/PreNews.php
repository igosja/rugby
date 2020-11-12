<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class PreNews
 * @package common\models\db
 *
 * @property int $id
 * @property string $new
 * @property string $error
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

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['error', 'new'], 'trim'],
            [['error', 'new'], 'string'],
        ];
    }
}
