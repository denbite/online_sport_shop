<?php


/** @var array $orders */

/** @var array $user */

use app\components\helpers\ValueHelper;
use app\models\Order;

?>


<div class="my-account-wrapper pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- My Account Page Start -->
                <div class="myaccount-page-wrapper">
                    <!-- My Account Tab Menu Start -->
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="myaccount-tab-menu nav" role="tablist">
                                <a href="#dashboad" class="active" data-toggle="tab">Главная</a>
                                <a href="#orders" data-toggle="tab"> Заказы</a>
                                <!--                                <a href="-->
                                <? //= \yii\helpers\Url::to([ '/user/default/logout' ]) ?><!--"> Выйти</a>-->
                            </div>
                        </div>
                        <!-- My Account Tab Menu End -->
                        <!-- My Account Tab Content Start -->
                        <div class="col-lg-9 col-md-8">
                            <div class="tab-content" id="myaccountContent">
                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Главная</h3>
                                        <div class="welcome">
                                            <p>Привет, <strong><?= $user['name'] ?></strong> (Если вы не
                                                <strong><?= $user['name'] ?></strong><a
                                                        href="<?= \yii\helpers\Url::to([ '/user/default/logout' ]) ?>"
                                                        class="logout"> Выйти</a>)</p>
                                        </div>

                                        <p class="mb-0 mt-3">
                                            В личном кабинете вы можете изменить свои контактые данные, а также
                                            посмотреть ваши заказы.
                                        </p>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->
                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade" id="orders" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Заказы</h3>
                                        <div class="myaccount-table table-responsive text-center">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>Заказ</th>
                                                    <th>Дата</th>
                                                    <th>Статус</th>
                                                    <th>Накладная</th>
                                                    <th>Сумма</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($orders as $order): ?>
                                                    <tr>
                                                        <td><?= $order['id'] ?></td>
                                                        <td><?= Yii::$app->formatter->asDate($order['created_at'],
                                                                                             'long') ?></td>
                                                        <td><?= Order::getStatuses()[$order['status']] ?></td>
                                                        <td><?= $order['invoice'] ?></td>
                                                        <td><?= ValueHelper::addCurrency($order['sum']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->
                            </div>
                        </div> <!-- My Account Tab Content End -->
                    </div>
                </div> <!-- My Account Page End -->
            </div>
        </div>
    </div>
</div>
