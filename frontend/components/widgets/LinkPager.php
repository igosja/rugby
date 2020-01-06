<?php

namespace frontend\components\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class LinkPager extends \yii\widgets\LinkPager
{
    /**
     * @return string
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        if ($currentPage >= 2) {
            $buttons[] = $this->renderPageButton(
                '1',
                0,
                null,
                false,
                false
            );
        }

        // prev page
        if ($currentPage > 0) {
            $buttons[] = $this->renderPageButton(
                $currentPage,
                $currentPage - 1,
                null,
                false,
                false
            );
        }

        // current page
        $buttons[] = $this->renderPageButton(
            $currentPage + 1,
            $currentPage,
            null,
            true,
            true
        );

        // next page
        if ($currentPage < $pageCount - 2) {
            $buttons[] = $this->renderPageButton(
                $currentPage + 2,
                $currentPage + 1,
                null,
                false,
                false
            );
        }

        // last page
        if ($currentPage <= $pageCount - 2) {
            $buttons[] = $this->renderPageButton(
                $pageCount,
                $pageCount - 1,
                null,
                false,
                false
            );
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        return Html::tag($tag, implode("\n", $buttons), $options);
    }

    /**
     * @param string $label
     * @param integer $page
     * @param string $class
     * @param boolean $disabled
     * @param boolean $active
     * @return string
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = $this->linkContainerOptions;
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        if ($active) {
            $options['class'] = $this->activePageCssClass;
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::a($label, $this->pagination->createUrl($page), $options);
    }
}
