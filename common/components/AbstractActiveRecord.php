<?php

// TODO refactor

namespace common\components;

use yii\db\ActiveRecord;

/**
 * Class AbstractActiveRecord
 * @package common\components
 */
abstract class AbstractActiveRecord extends ActiveRecord
{
    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        return parent::save($runValidation, $attributeNames);
    }
}
