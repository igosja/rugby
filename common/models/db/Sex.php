<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Sex
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 */
class Sex extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%sex}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 10],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return array
     */
    public static function selectOptions(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
