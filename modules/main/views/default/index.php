<?php

/** @var array $banners */

?>


<div class="slider-area section-padding-1">
    <div class="slider-active-2 owl-carousel nav-style-2 dot-style-1">
        <?php foreach ($banners as $banner): ?>
            <div class="single-slider slider-height-2 bg-aliceblue">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 col-sm-6">
                            <div class="slider-content pt-180 slider-animated-1">
                                <h1 class="animated"><?= $banner['title'] ?></h1>
                                <p class="animated"><?= $banner['description'] ?></p>
                                <div class="slider-btn btn-hover">
                                    <a class="animated" href="<?= $banner['link'] ?>"><?= $banner['link_title'] ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 col-sm-6">
                            <div class="slider-single-img-2 slider-animated-1">
                                <?= \yii\helpers\Html::img(\app\models\Image::getLink($banner['image']['id']), [
                                    'class' => 'animated',
                                    'alt' => $banner['image']['url'],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>