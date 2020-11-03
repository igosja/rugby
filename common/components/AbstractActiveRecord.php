<?php

namespace common\components;

use common\components\helpers\ErrorHelper;
use RuntimeException;
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
        if (!$this->validate()) {
            return false;
        }
        if (!parent::save($runValidation, $attributeNames)) {
            throw new RuntimeException(ErrorHelper::modelErrorsToString($this));
        }

        return true;
    }
}
