<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Class Rule
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $order
 * @property string $text
 * @property string $title
 */
class Rule extends AbstractActiveRecord
{
    public const SEARCH_SYMBOLS = 200;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rule}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['order', 'title', 'text'], 'required'],
            [['order'], 'integer', 'min' => 1, 'max' => 99],
            [['title', 'text'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['order', 'title'], 'unique'],
        ];
    }

    /**
     * @return string
     */
    public function formatSearchText(): string
    {
        $text = strip_tags($this->text);
        $startPosition = mb_stripos($text, Yii::$app->request->get('q')) - self::SEARCH_SYMBOLS;
        if ($startPosition < 0) {
            $startPosition = 0;
        }
        $length = mb_strlen(Yii::$app->request->get('q')) + self::SEARCH_SYMBOLS * 2;
        $text = '...' . mb_substr($text, $startPosition, $length) . '...';
        $text = str_ireplace(
            Yii::$app->request->get('q'),
            '<span class="info">' . Yii::$app->request->get('q') . '</span>',
            $text
        );

        return $text;
    }
}
