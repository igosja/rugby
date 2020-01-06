<?php

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
    public $items;

    /**
     * @var string $bar
     */
    private $bar;

    /**
     * @var array $item
     */
    private $item;

    /**
     * @var string $route
     */
    private $route;

    /**
     * @var array $params
     */
    private $params;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->renderBar();
        return $this->bar;
    }

    /**
     * @return void
     */
    private function renderBar()
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
    private function addUrlToAlias()
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
    private function renderItem()
    {
        if ($this->isActive()) {
            return Html::tag('span', $this->item['text'], ['class' => 'strong']);
        } else {
            return Html::a($this->item['text'], $this->item['url']);
        }
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        if (isset($this->item['alias']) && is_array($this->item['alias'])) {
            foreach ($this->item['alias'] as $alias) {
                if (isset($alias) && is_array($alias) && isset($alias[0])) {
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
                                } elseif ($this->params[$name] != $value) {
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
