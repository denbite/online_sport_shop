<?php

/** @var array $items */

/** @var string $totalCost */

$this->title = 'Корзина';

$this->params['breadcrumbs'][] = $this->title;

use app\components\helpers\ValueHelper;
use app\models\Image;
use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="cart-main-area pt-95 pb-100">
    <div class="container">
        <h3 class="cart-page-title">Ваши предметы</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <form action="#">
                    <div class="table-content table-responsive cart-table-content">
                        <table>
                            <thead>
                            <tr>
                                <th>Превью</th>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                                <th>Сумма</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr data-product="<?= ValueHelper::encryptValue($item['size']['id']) ?>">
                                    <td class="product-thumbnail">
                                        <?= Html::a(Html::img(Image::getLink($item['image']['id'],
                                                                             Image::SIZE_THUMBNAIL), [
                                                                  'alt' => $item['image']['url'],
                                                                  'width' => 90,
                                                                  'height' => 90,
                                                              ]),
                                                    [ '/main/products/product', 'slug' => ValueHelper::encryptValue($item['item']['id']) ]) ?>
                                    </td>
                                    <td class="product-name"><a
                                                href="<?= Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($item['item']['id']) ]) ?>"><?= $item['item']['firm'] . ' ' . $item['item']['model'] . ' ' . $item['size']['size'] ?>
                                        </a></td>
                                    <td class="product-price-cart"><span class="amount"><?= $item['price'] ?></span>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" type="text"
                                                   name="qtybutton"
                                                   value="<?= $item['quantity'] ?>" min="1">
                                        </div>
                                    </td>
                                    <td class="product-subtotal"><?= $item['cost'] ?></td>
                                    <td class="product-remove">
                                        <a href="#"><i class="sli sli-close"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="cart-shiping-update-wrapper">
                                <div class="cart-shiping-update">
                                    <a href="<?= \yii\helpers\Url::to([ '/main/products/catalog' ]) ?>">Продолжить
                                        покупки</a>
                                </div>
                                <div class="cart-clear">
                                    <a class="cart-close">Очистить корзину</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="offset-lg-8 col-lg-4 col-md-12">
                        <div class="grand-totall">
                            <div class="title-wrap">
                                <h4 class="cart-bottom-title section-bg-gary-cart">Итого</h4>
                            </div>
                            <div class="total-shipping">
                                <h5>Доставка</h5>
                                <ul>
                                    <li><input type="checkbox" checked disabled> Новая почта
                                        <span> <?= ValueHelper::getDelivery($totalCost) ?> </span>
                                    </li>
                                </ul>
                            </div>
                            <h4 class="grand-totall-title pt-4">Сумма чека <span> <?= $totalCost ?> </span></h4>
                            <a href="<?= Url::to([ '/main/checkout/index' ]) ?>">Оформить заказ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>