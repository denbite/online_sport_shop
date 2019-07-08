<?php

use app\components\helpers\ValueHelper;
use yii\helpers\Html;

/** @var array $current */
/** @var array $parents */
/** @var array $children */

$this->title = $current['name'];

if (!empty($parents)) {
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = [ 'url' => \yii\helpers\Url::to([ '/main/category/index', 'slug' => ValueHelper::encryptValue($parent['id']) ]), 'label' => $parent['name'] ];
    }
}

$this->params['breadcrumbs'][] = $current['name'];

?>


<div class="blog-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <?php foreach ($children as $child): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="blog-wrap mb-40 text-center scroll-zoom">
                        <div class="blog-img mb-25">
                            <?= Html::a(Html::img("/files/Category/Category-{$child['id']}/{$child['image']['url']}",
                                [ 'alt' => $child['name'] ]),
                                [ '/main/category/index', 'slug' => ValueHelper::encryptValue($child['id']) ]) ?>
                        </div>
                        <div class="blog-content">
                            <h3><?= $child['name'] ?></h3>
                            <?php if (!empty($child['description'])): ?>
                                <p><?= $child['description'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pro-pagination-style text-center mt-20 pagination-mrg-xs-none">
            <ul>
                <li><a class="prev" href="#"><i class="sli sli-arrow-left"></i></a></li>
                <li><a class="active" href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a class="next" href="#"><i class="sli sli-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
</div>