<?php

// TODO refactor

namespace frontend\components;

use yii\web\Request;

/**
 * Class FrontendRequest
 * @package frontend\components
 */
class FrontendRequest extends Request
{
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function resolvePathInfo(): string
    {
        return trim(parent::resolvePathInfo(), '/');
    }
}
