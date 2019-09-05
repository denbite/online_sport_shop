<?php

namespace app\components\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class AdminPaginator
    extends \yii\widgets\LinkPager
{
    
    public $options = [ 'class' => 'pagination mx-30 pb-10', 'style' => 'float:right;' ];
    
    public $activePageCssClass = 'current';
    
    public $disabledPageCssClass = null;
    
    public $prevPageLabel = 'Previous';
    
    public $nextPageLabel = 'Next';
    
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
    
}