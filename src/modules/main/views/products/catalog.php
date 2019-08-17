<?php

use app\components\helpers\ValueHelper;
use app\components\widgets\Paginator;
use app\models\Image;
use yii\helpers\Html;

/** @var array $current */
/** @var array $parents */
/** @var array $items */
/** @var array $pages */

$this->title = $current['name'];

if (!empty($parents)) {
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = [ 'url' => \yii\helpers\Url::to([ '/main/products/category', 'slug' => $parent['lvl'] != 0 ? ValueHelper::encryptValue($parent['id']) : false ]), 'label' => $parent['name'] ];
    }
}

$this->params['breadcrumbs'][] = $current['name'];

?>

<div class="shop-area pt-95 pb-100">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="shop-top-bar">
                    <div class="select-shoing-wrap">
                        <!--                        <div class="shop-select">-->
                        <!--                            <select>-->
                        <!--                                <option value="">Sort by newness</option>-->
                        <!--                                <option value="">A to Z</option>-->
                        <!--                                <option value=""> Z to A</option>-->
                        <!--                                <option value="">In stock</option>-->
                        <!--                            </select>-->
                        <!--                        </div>-->
                        <p><?= "Найдено: {$pages->totalCount} шт." ?></p>
                    </div>
                    <div class="shop-tab nav">
                        <a class="active" href="#vertical" data-toggle="tab">
                            <i class="sli sli-grid"></i>
                        </a>
                        <a href="#horizontal" data-toggle="tab">
                            <i class="sli sli-menu"></i>
                        </a>
                    </div>
                </div>
                <div class="shop-bottom-area mt-35">
                    <div class="tab-content jump">
                        <div id="vertical" class="tab-pane active">
                            <div class="row ht-products">
                                <?php foreach ($items as $item): ?>
                                    <div class="col-xl-4 col-md-6 col-lg-6 col-sm-6">
                                        <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                                            <div class="ht-product-inner">
                                                <div class="ht-product-image-wrap">
                                                    <span class="ht-product-label ht-product-label-left">NEW</span>
                                                    <?php if (!empty($item['promotion'])): ?>
                                                        <span class="ht-product-label ht-product-label-right">Sale</span>
                                                    <?php endif; ?>
                                                    <a href="<?= \yii\helpers\Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($item['id']) ]) ?>"
                                                       class="ht-product-image">
                                                        <?= Html::img(Image::getLink($item['allColors'][0]['mainImage']['id'],
                                                                                     Image::SIZE_MEDIUM),
                                                                      [ 'alt' => $item['allColors'][0]['mainImage']['url'] ]) ?>
                                                        <div class="ht-product-action">
                                                            <ul>
                                                                <!--                                                                <li>-->
                                                                <!--                                                                    <a href="#"-->
                                                                <!--                                                                       data-toggle="modal"-->
                                                                <!--                                                                       data-target="#exampleModal"><i-->
                                                                <!--                                                                                class="sli sli-magnifier"></i><span-->
                                                                <!--                                                                                class="ht-product-action-tooltip">Quick View</span></a>-->
                                                                <!--                                                                </li>-->
                                                                <!--                                                                <li>-->
                                                                <!--                                                                    <a href="#"><i-->
                                                                <!--                                                                                class="sli sli-heart"></i><span-->
                                                                <!--                                                                                class="ht-product-action-tooltip">В список желаний</span></a>-->
                                                                <!--                                                                </li>-->
                                                                <!--                                                                <li>-->
                                                                <!--                                                                    <a href="#"><i-->
                                                                <!--                                                                                class="sli sli-refresh"></i><span-->
                                                                <!--                                                                                class="ht-product-action-tooltip">Add to Compare</span></a>-->
                                                                <!--                                                                </li>-->
                                                                <!--                                                                <li>-->
                                                                <!--                                                                    <a href="#"><i-->
                                                                <!--                                                                                class="sli sli-bag"></i><span-->
                                                                <!--                                                                                class="ht-product-action-tooltip">Добавить в корзину</span></a>-->
                                                                <!--                                                                </li>-->
                                                            </ul>
                                                        </div>
                                                </div>
                                                <div class="ht-product-content">
                                                    <div class="ht-product-content-inner">
                                                        <h4 class="ht-product-title"><a
                                                                    href="<?= \yii\helpers\Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($item['id']) ]) ?>"><?= $item['firm'] . ' ' . $item['model'] ?></a>
                                                        </h4>
                                                        <div class="ht-product-price">
                                                            <span class="new"> <?= ValueHelper::outPriceCatalog($item['allColors'],
                                                                                                                $item['promotion'])
                                                                ?> </span>
                                                            <?php if (!empty($item['promotion'])): ?>
                                                                <span class="old"> <?= ValueHelper::outPriceCatalog($item['allColors'],
                                                                                                                    []) ?> </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="ht-product-ratting-wrap">
                                                                <span class="ht-product-ratting">
                                                                    
                                                                    <span class="ht-product-user-ratting"
                                                                          style="width: 100%;">
<!--                                                                        current rating for item-->
                                                                        <?php for ($i = 0; $i < round($item['rate'] / 20); $i++): ?>
                                                                            <i class="sli sli-star"></i>
                                                                        <?php endfor; ?>
                                                                    </span>
                                                                    <!--                                                                    max count of stars-->
                                                                    <i class="sli sli-star"></i>
                                                                    <i class="sli sli-star"></i>
                                                                    <i class="sli sli-star"></i>
                                                                    <i class="sli sli-star"></i>
                                                                    <i class="sli sli-star"></i>
                                                                </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="horizontal" class="tab-pane">
                            <?php foreach ($items as $item): ?>
                                <div class="shop-list-wrap shop-list-mrg2 shop-list-mrg-none mb-30">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4">
                                            <div class="product-list-img">
                                                <a href="<?= \yii\helpers\Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($item['id']) ]) ?>">
                                                    <?= Html::img(Image::getLink($item['allColors'][0]['mainImage']['id']),
                                                                  [ 'alt' => $item['allColors'][0]['mainImage']['url'] ]) ?>
                                                </a>
                                                <!--                                                <div class="product-quickview">-->
                                                <!--                                                    <a href="#" title="Quick View" data-toggle="modal"-->
                                                <!--                                                       data-target="#exampleModal"><i class="sli sli-magnifier-add"></i></a>-->
                                                <!--                                                </div>-->
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 align-self-center">
                                            <div class="shop-list-content">
                                                <h3>
                                                    <a href="<?= \yii\helpers\Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($item['id']) ]) ?>"><?= $item['firm'] . ' ' . $item['model'] ?></a>
                                                </h3>
                                                <?= $item['description']['small_text'] ?>
                                                <div class="shop-list-price-action-wrap">
                                                    <div class="shop-list-price-ratting">
                                                        <div class="ht-product-list-price">
                                                            <span class="new"> <?= ValueHelper::outPriceCatalog($item['allColors'],
                                                                                                                $item['promotion'])
                                                                ?> </span>
                                                            <?php if (!empty($item['promotion'])): ?>
                                                                <span class="old"> <?= ValueHelper::outPriceCatalog($item['allColors'],
                                                                                                                    []) ?> </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="ht-product-list-ratting">
                                                            <i class="sli sli-star"></i>
                                                            <i class="sli sli-star"></i>
                                                            <i class="sli sli-star"></i>
                                                            <i class="sli sli-star"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ht-product-list-action">
                                                        <!--                                                        <a class="list-wishlist" title="Add To Wishlist" href="#"><i-->
                                                        <!--                                                                    class="sli sli-heart"></i></a>-->
                                                        <!--                                                        <a class="list-cart" title="Add To Cart" href="#"><i-->
                                                        <!--                                                                    class="sli sli-basket-loaded"></i>Добавить в корзину</a>-->
                                                        <!--                                                        <a class="list-refresh" title="Add To Compare" href="#"><i-->
                                                        <!--                                                                    class="sli sli-refresh"></i></a>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
    
                    <?=
                    Paginator::widget([
                                          'pagination' => $pages,
                                      ])
                    ?>

                </div>
            </div>
            <div class="col-lg-3">
                <div class="sidebar-style mr-30">
                    <div class="sidebar-widget">
                        <h4 class="pro-sidebar-title">Поиск </h4>
                        <div class="pro-sidebar-search mb-50 mt-25">
                            <form class="pro-sidebar-search-form" action="#">
                                <input type="text" placeholder="Введите товар">
                                <button>
                                    <i class="sli sli-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


