<?php

use app\components\helpers\ValueHelper;
use app\models\Image;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $item */
/** @var array $parents */

$this->title = $item['firm'] . ' ' . $item['model'];

foreach ($parents as $parent) {
    $this->params['breadcrumbs'][] = [ 'url' => Url::to([ '/main/products/category', 'slug' => $parent['lvl'] != 0 ? ValueHelper::encryptValue($parent['id']) : null ]), 'label' => $parent['name'] ];
}

$class = Image::getTypes()[Image::TYPE_ITEM];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="product-details-area pt-100 pb-95">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product-details-img">
                    <div id="preview" class="zoompro-border zoompro-span">
                        <?= Html::img("/files/{$class}/{$class}-{$item['allColors'][0]['id']}/{$item['allColors'][0]['allImages'][0]['url']}",
                            [
                                'class' => 'zoompro',
                                'data-zoom-image' => "/files/{$class}/{$class}-{$item['allColors'][0]['id']}/{$item['allColors'][0]['allImages'][0]['url']}",
                                'alt' => $item['allColors'][0]['allImages'][0]['url'],
                            ]) ?>
                        <!--                        <div class="product-video">-->
                        <!--                            <a class="video-popup" href="https://www.youtube.com/watch?v=tce_Ap96b0c">-->
                        <!--                                <i class="sli sli-control-play"></i>-->
                        <!--                                View Video-->
                        <!--                            </a>-->
                        <!--                        </div>-->
                    </div>
                    <div id="gallery" class="mt-20 product-dec-slider">
                        <?php foreach ($item['allColors'] as $color): ?>
                            <?php foreach ($color['allImages'] as $image): ?>
                                <?= Html::a(Html::img("/files/{$class}/{$class}-{$color['id']}/{$image['url']}",
                                [ 'alt' => $image['url'], 'width' => 90, 'height' => 90 ]), null, [
                                    'data-image' => "/files/{$class}/{$class}-{$color['id']}/{$image['url']}",
                                    'data-zoom-image' => "/files/{$class}/{$class}-{$color['id']}/{$image['url']}",
                                    'data-color' => $color['id'],
                            ]) ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div id="price-details" class="product-details-content ml-30">
                    <h2><?= $this->title ?></h2>
                    <?php foreach ($item['allColors'] as $color): ?>
                        <?php foreach ($color['allSizes'] as $size): ?>
                            <?php if ($size['quantity'] > 0): ?>
                                <div class="product-details-price"
                                     data-color="<?= ValueHelper::encryptValue($color['id']) ?>"
                                     data-size="<?= ValueHelper::encryptValue($size['id']) ?>" style="display: none;">
                                    <span><?= ValueHelper::formatPrice($size['price']) ?></span>
                                    <!--                                    make promotions check and display old value-->
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <div id="no-stock" class="product-details-price" style="display: none">
                        <span style="color:#3b4552;"> Нет в наличии </span>
                    </div>
                    <div class="pro-details-rating-wrap">
                        <div class="pro-details-rating">
                            <i class="sli sli-star yellow"></i>
                            <?php for ($i = 0; $i < round($item['rate'] / 25); $i++): ?>
                                <i class="sli sli-star yellow"></i>
                            <?php endfor; ?>
                        </div>
                        <span><a href="#">0 отзывов</a></span>
                    </div>
                    <?= $item['description']['small_text'] ?>
                    <div class="pro-details-list">
                        <ul>
                            <?php foreach (explode(';', $item['description']['small_list']) as $one): ?>
                                <li>- <?= $one ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="pro-details-size-color">
                        <div class="pro-details-color-wrap">
                            <span>Цвет</span>
                            <div class="pro-details-color-content">
                                <ul id="color-details">
                                    <?php foreach ($item['allColors'] as $index => $color): ?>
                                        <li data-color="<?= ValueHelper::encryptValue($color['id']) ?>" <?= !$index ? ' class="active"' : '' ?>
                                            style="background-color: <?= $color['html'] ?>;"></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="pro-details-size">
                            <span>Размер</span>
                            <div id="size-details" class="pro-details-size-content">
                                <?php foreach ($item['allColors'] as $color): ?>
                                    <?php $first = true ?>
                                    <ul data-color="<?= ValueHelper::encryptValue($color['id']) ?>"
                                        style="display: none">
                                        <?php foreach ($color['allSizes'] as $size): ?>
                                            <?php if ($size['quantity'] > 0 and $first): ?>
                                                <li>
                                                    <a data-size="<?= ValueHelper::encryptValue($size['id']) ?>"
                                                       class="active"><?= $size['size'] ?></a>
                                                </li>
                                                <?php $first = false;
                                            else: ?>
                                                <li>
                                                    <a data-size="<?= ValueHelper::encryptValue($size['id']) ?>"><?= $size['size'] ?></a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endforeach; ?>
                                </div>
                        </div>
                    </div>
                    <div class="pro-details-quality">
                        <div class="cart-plus-minus">
                            <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1">
                        </div>
                        <div class="pro-details-cart btn-hover">
                            <a href="#">Добавить в корзину</a>
                        </div>
                        <!--                        <div class="pro-details-wishlist">-->
                        <!--                            <a title="Add To Wishlist" href="#"><i class="sli sli-heart"></i></a>-->
                        <!--                        </div>-->
                        <!--                        <div class="pro-details-compare">-->
                        <!--                            <a title="Add To Compare" href="#"><i class="sli sli-refresh"></i></a>-->
                        <!--                        </div>-->
                    </div>
                    <div class="pro-details-meta">
                        <span>Categories :</span>
                        <ul>
                            <li><a href="#">Minimal,</a></li>
                            <li><a href="#">Furniture,</a></li>
                            <li><a href="#">Fashion</a></li>
                        </ul>
                    </div>
                    <div class="pro-details-meta">
                        <span>Tag :</span>
                        <ul>
                            <li><a href="#">Fashion, </a></li>
                            <li><a href="#">Furniture,</a></li>
                            <li><a href="#">Electronic</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="description-review-area pb-95">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="description-review-wrapper">
                    <div class="description-review-topbar nav">
                        <a class="active" data-toggle="tab" href="#des-details1">Description</a>
                        <a data-toggle="tab" href="#des-details3">Additional information</a>
                        <a data-toggle="tab" href="#des-details2">Reviews (0)</a>
                    </div>
                    <div class="tab-content description-review-bottom">
                        <div id="des-details1" class="tab-pane active">
                            <div class="product-description-wrapper">
                                <?= $item['description']['text'] ?>
                            </div>
                        </div>
                        <div id="des-details3" class="tab-pane">
                            <div class="product-anotherinfo-wrapper">
                                <ul>
                                    <?php foreach (explode(';', $item['description']['list']) as $one): ?>
                                        <?php $parts = explode(':', $one);
                                        if (!empty($parts) and count($parts) == 2):
                                            ?>
                                            <li><span><?= $parts[0] ?></span> <?= $parts[1] ?></li>
                                        <?php
                                        endif;
                                    endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div id="des-details2" class="tab-pane">
                            <div class="review-wrapper">
                                <div class="single-review">
                                    <div class="review-img">
                                        <img src="/images/main/product-details/client-1.jpg" alt="">
                                    </div>
                                    <div class="review-content">
                                        <p>“In convallis nulla et magna congue convallis. Donec eu nunc vel justo
                                            maximus posuere. Sed viverra nunc erat, a efficitur nibh”</p>
                                        <div class="review-top-wrap">
                                            <div class="review-name">
                                                <h4>Stella McGee</h4>
                                            </div>
                                            <div class="review-rating">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-review">
                                    <div class="review-img">
                                        <img src="/images/main/product-details/client-2.jpg" alt="">
                                    </div>
                                    <div class="review-content">
                                        <p>“In convallis nulla et magna congue convallis. Donec eu nunc vel justo
                                            maximus posuere. Sed viverra nunc erat, a efficitur nibh”</p>
                                        <div class="review-top-wrap">
                                            <div class="review-name">
                                                <h4>Stella McGee</h4>
                                            </div>
                                            <div class="review-rating">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-review">
                                    <div class="review-img">
                                        <img src="/images/main/product-details/client-3.jpg" alt="">
                                    </div>
                                    <div class="review-content">
                                        <p>“In convallis nulla et magna congue convallis. Donec eu nunc vel justo
                                            maximus posuere. Sed viverra nunc erat, a efficitur nibh”</p>
                                        <div class="review-top-wrap">
                                            <div class="review-name">
                                                <h4>Stella McGee</h4>
                                            </div>
                                            <div class="review-rating">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ratting-form-wrapper">
                                <span>Add a Review</span>
                                <p>Your email address will not be published. Required fields are marked <span>*</span>
                                </p>
                                <div class="star-box-wrap">
                                    <div class="single-ratting-star">
                                        <i class="sli sli-star"></i>
                                    </div>
                                    <div class="single-ratting-star">
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                    </div>
                                    <div class="single-ratting-star">
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                    </div>
                                    <div class="single-ratting-star">
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                    </div>
                                    <div class="single-ratting-star">
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                    </div>
                                </div>
                                <div class="ratting-form">
                                    <form action="#">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="rating-form-style mb-20">
                                                    <label>Your review <span>*</span></label>
                                                    <textarea name="Your Review"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="rating-form-style mb-20">
                                                    <label>Name <span>*</span></label>
                                                    <input type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="rating-form-style mb-20">
                                                    <label>Email <span>*</span></label>
                                                    <input type="email">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-submit">
                                                    <input type="submit" value="Submit">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="pro-dec-banner">
                    <a href="#"><img src="/images/main/banner/banner-15.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="product-area pb-70">
    <div class="container">
        <div class="section-title text-center pb-60">
            <h2>Related products</h2>
            <p> Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of
                classical</p>
        </div>
        <div class="arrivals-wrap scroll-zoom">
            <div class="ht-products product-slider-active owl-carousel">
                <!--Product Start-->
                <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                    <div class="ht-product-inner">
                        <div class="ht-product-image-wrap">
                            <a href="product-details.html" class="ht-product-image"> <img
                                        src="/images/main/product/product-5.svg" alt="Universal Product Style"> </a>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#" data-toggle="modal" data-target="#exampleModal"><i
                                                    class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ht-product-content">
                            <div class="ht-product-content-inner">
                                <div class="ht-product-categories"><a href="#">Clock</a></div>
                                <h4 class="ht-product-title"><a href="#">Demo Product Name</a></h4>
                                <div class="ht-product-price">
                                    <span class="new">$60.00</span>
                                </div>
                                <div class="ht-product-ratting-wrap">
                                        <span class="ht-product-ratting">
                                            <span class="ht-product-user-ratting" style="width: 100%;">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </span>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#"><i class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="ht-product-countdown-wrap">
                                <div class="ht-product-countdown" data-countdown="2020/01/01"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Product End-->
                <!--Product Start-->
                <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                    <div class="ht-product-inner">
                        <div class="ht-product-image-wrap">
                            <a href="product-details.html" class="ht-product-image"> <img
                                        src="/images/main/product/product-6.svg" alt="Universal Product Style"> </a>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#" data-toggle="modal" data-target="#exampleModal"><i
                                                    class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ht-product-content">
                            <div class="ht-product-content-inner">
                                <div class="ht-product-categories"><a href="#">Lamp </a></div>
                                <h4 class="ht-product-title"><a href="#">Demo Product Name</a></h4>
                                <div class="ht-product-price">
                                    <span class="new">$50.00</span>
                                    <span class="old">$80.00</span>
                                </div>
                                <div class="ht-product-ratting-wrap">
                                        <span class="ht-product-ratting">
                                            <span class="ht-product-user-ratting" style="width: 90%;">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </span>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#"><i class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="ht-product-countdown-wrap">
                                <div class="ht-product-countdown" data-countdown="2020/01/01"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Product End-->
                <!--Product Start-->
                <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                    <div class="ht-product-inner">
                        <div class="ht-product-image-wrap">
                            <a href="product-details.html" class="ht-product-image"> <img
                                        src="/images/main/product/product-7.svg" alt="Universal Product Style"> </a>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#" data-toggle="modal" data-target="#exampleModal"><i
                                                    class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ht-product-content">
                            <div class="ht-product-content-inner">
                                <div class="ht-product-categories"><a href="#">Chair</a></div>
                                <h4 class="ht-product-title"><a href="#">Demo Product Name</a></h4>
                                <div class="ht-product-price">
                                    <span class="new">$30.00</span>
                                </div>
                                <div class="ht-product-ratting-wrap">
                                        <span class="ht-product-ratting">
                                            <span class="ht-product-user-ratting" style="width: 100%;">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </span>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#"><i class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="ht-product-countdown-wrap">
                                <div class="ht-product-countdown" data-countdown="2020/01/01"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Product End-->
                <!--Product Start-->
                <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                    <div class="ht-product-inner">
                        <div class="ht-product-image-wrap">
                            <a href="product-details.html" class="ht-product-image"> <img
                                        src="/images/main/product/product-8.svg" alt="Universal Product Style"> </a>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#" data-toggle="modal" data-target="#exampleModal"><i
                                                    class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ht-product-content">
                            <div class="ht-product-content-inner">
                                <div class="ht-product-categories"><a href="#">Chair</a></div>
                                <h4 class="ht-product-title"><a href="#">Demo Product Name</a></h4>
                                <div class="ht-product-price">
                                    <span class="new">$60.00</span>
                                    <span class="old">$90.00</span>
                                </div>
                                <div class="ht-product-ratting-wrap">
                                        <span class="ht-product-ratting">
                                            <span class="ht-product-user-ratting" style="width: 100%;">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </span>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#"><i class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="ht-product-countdown-wrap">
                                <div class="ht-product-countdown" data-countdown="2020/01/01"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Product End-->
                <!--Product Start-->
                <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                    <div class="ht-product-inner">
                        <div class="ht-product-image-wrap">
                            <a href="product-details.html" class="ht-product-image"> <img
                                        src="/images/main/product/product-6.svg" alt="Universal Product Style"> </a>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#" data-toggle="modal" data-target="#exampleModal"><i
                                                    class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ht-product-content">
                            <div class="ht-product-content-inner">
                                <div class="ht-product-categories"><a href="#">Lamp </a></div>
                                <h4 class="ht-product-title"><a href="#">Demo Product Name</a></h4>
                                <div class="ht-product-price">
                                    <span class="new">$50.00</span>
                                    <span class="old">$80.00</span>
                                </div>
                                <div class="ht-product-ratting-wrap">
                                        <span class="ht-product-ratting">
                                            <span class="ht-product-user-ratting" style="width: 90%;">
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                                <i class="sli sli-star"></i>
                                            </span>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        <i class="sli sli-star"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="ht-product-action">
                                <ul>
                                    <li><a href="#"><i class="sli sli-magnifier"></i><span
                                                    class="ht-product-action-tooltip">Quick View</span></a></li>
                                    <li><a href="#"><i class="sli sli-heart"></i><span
                                                    class="ht-product-action-tooltip">Add to Wishlist</span></a></li>
                                    <li><a href="#"><i class="sli sli-refresh"></i><span
                                                    class="ht-product-action-tooltip">Add to Compare</span></a></li>
                                    <li><a href="#"><i class="sli sli-bag"></i><span class="ht-product-action-tooltip">Add to Cart</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="ht-product-countdown-wrap">
                                <div class="ht-product-countdown" data-countdown="2020/01/01"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Product End-->
            </div>
        </div>
    </div>
</div>