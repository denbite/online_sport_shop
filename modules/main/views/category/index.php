<?php

use app\components\helpers\ValueHelper;
use yii\helpers\Html;

$this->title = 'Категории';

$this->params['breadcrumbs'][] = $this->title;

?>


<div class="blog-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="blog-wrap mb-40 text-center scroll-zoom">
                        <div class="blog-img mb-25">
                            <?= Html::a(Html::img("/files/Category/Category-{$item['id']}/{$item['image']['url']}",
                                                  [ 'alt' => $item['name'] ]),
                                        [ '/main/category/index', 'slug' => ValueHelper::encryptValue($item['id']) ]) ?>
                        </div>
                        <div class="blog-content">
                            <h3><?= $item['name'] ?></h3>
                            <?php if (!empty($item['description'])): ?>
                                <p><?= $item['description'] ?></p>
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