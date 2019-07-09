<?php

namespace app\components\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Paginator
    extends \yii\widgets\LinkPager
{
    
    public $options = [ 'class' => null ];
    
    public $activePageCssClass = 'active';
    
    public $disabledPageCssClass = null;
    
    public $prevPageLabel = '<i class="sli sli-arrow-left"></i>';
    
    public $nextPageLabel = '<i class="sli sli-arrow-right"></i>';
    
    public $wrapOptions = [ 'class' => 'pro-pagination-style text-center mt-30' ];
    
    /**
     * Renders a page button.
     * You may override this method to customize the generation of page buttons.
     *
     * @param string $label the text label for the button
     * @param int    $page the page number
     * @param string $class the CSS class for the page button.
     * @param bool   $disabled whether this page button is disabled
     * @param bool   $active whether this page button is active
     *
     * @return string the rendering result
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = $this->linkContainerOptions;
        
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($linkOptions, empty($class) ? $this->pageCssClass : $class);
        
        if ($active) {
            Html::addCssClass($linkOptions, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($linkOptions, $this->disabledPageCssClass);
            $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'a');
            
            return Html::tag($linkWrapTag, Html::tag($tag, $label, $linkOptions), $options);
        }
        
        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }
    
    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        return Html::tag('div', parent::renderPageButtons(), $this->wrapOptions);
    }
    
}