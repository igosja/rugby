<?php

// TODO refactor

namespace frontend\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class LinkBar
 * @package common\widgets
 *
 * @property string $bar
 * @property array $item
 * @property array $items
 * @property array $params
 * @property string $route
 */
class LinkBar extends Widget
{
    /**
     * @var array $items
     */
    public array $items = [];

    /**
     * @var string $bar
     */
    private string $bar = '';

    /**
     * @var array $item
     */
    private array $item = [];

    /**
     * @var string $route
     */
    private string $route = '';

    /**
     * @var array $params
     */
    private array $params = [];

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        if ('' === $this->route && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ([] === $this->params) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $this->renderBar();
        return $this->bar;
    }

    /**
     * @return void
     */
    private function renderBar(): void
    {
        $result = [];
        foreach ($this->items as $item) {
            $this->item = $item;
            $this->addUrlToAlias();
            $result[] = $this->renderItem();
        }
        $this->bar = implode(' | ', $result);
    }

    /**
     * @return void
     */
    private function addUrlToAlias(): void
    {
        if (!isset($this->item['alias'])) {
            $this->item['alias'] = [$this->item['url']];
        } else {
            $this->item['alias'][] = $this->item['url'];
        }
    }

    /**
     * @return string
     */
    private function renderItem(): string
    {
        if ($this->isActive()) {
            return Html::tag('span', $this->item['text'], ['class' => 'strong']);
        }

        return Html::a($this->item['text'], $this->item['url']);
    }

    /**
     * @return bool
     */
    private function isActive(): bool
    {
        if (isset($this->item['alias']) && is_array($this->item['alias'])) {
            foreach ($this->item['alias'] as $alias) {
                if (isset($alias[0]) && is_array($alias)) {
                    $route = $alias[0];
                    if ($route[0] !== '/' && Yii::$app->controller) {
                        $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
                    }
                    if (ltrim($route, '/') !== $this->route) {
                        continue;
                    }
                    unset($alias['#']);
                    if (count($alias) > 1) {
                        $params = $alias;
                        unset($params[0]);
                        foreach ($params as $name => $value) {
                            if ($value !== null) {
                                if (!isset($this->params[$name])) {
                                    continue;
                                }

                                if ($this->params[$name] !== $value) {
                                    return false;
                                }
                            }
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }
}
