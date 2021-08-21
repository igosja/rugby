<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TranslateKey
 * @package common\models\db
 *
 * @property int $id
 * @property string $category
 * @property string $message
 * @property string $text
 *
 * @property-read TranslateOption[] $translateOptions
 */
class TranslateKey extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%translate_key}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['category', 'message', 'text'], 'required'],
            [['category', 'message'], 'string', 'max' => 255],
            [['text'], 'string'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslateOptions(): ActiveQuery
    {
        return $this->hasMany(TranslateOption::class, ['translate_key_id' => 'id']);
    }
}
