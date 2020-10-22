<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Double
 * @package common\models\db
 *
 * @property int $id
 * @property int $child_user_id
 * @property int $count
 * @property int $date
 * @property int $parent_user_id
 *
 * @property-read User $childUser
 * @property-read User $parentUser
 */
class Double extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%double}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['child_user_id', 'count', 'parent_user_id'], 'required'],
            [['child_user_id', 'parent_user_id'], 'integer', 'min' => 1],
            [['count'], 'integer', 'min' => 1, 'max' => 999],
            [['child_user_id'], 'exist', 'targetRelation' => 'childUser'],
            [['parent_user_id'], 'exist', 'targetRelation' => 'parentUser'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getChildUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'child_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getParentUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'parent_user_id']);
    }
}
