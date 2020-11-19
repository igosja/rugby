<?php

// TODO refactor

namespace common\components;

use common\components\helpers\ErrorHelper;
use yii\db\ActiveRecord;
use yii\db\Exception;

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
     * @throws Exception
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            print '<pre>';
            print_r($this);
            exit;
            throw new Exception(ErrorHelper::modelErrorsToString($this));
        }
        return true;
    }
}
