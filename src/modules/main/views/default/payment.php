<?php


$this->title = 'Оплата';

$this->params['breadcrumbs'][] = $this->title;


?>

<div class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 pb-50">
                <h2 style="font-weight: 600;margin: 0 0 15px;font-size: 35px;line-height: 30px;">
                    Способы оплаты
                </h2>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="contact-from contact-shadow ml-0">
                    <?= \yii\helpers\Html::img('/images/payment-cash.png',
                                               [
                                                   'class' => 'col-12 pb-20',
                                                   'alt' => 'cash',
                                               ]) ?>
                    <p>
                        Оплата наличными при получении товара
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
