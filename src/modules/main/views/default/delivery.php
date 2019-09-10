<?php


$this->title = 'Доставка';

$this->params['breadcrumbs'][] = $this->title;


?>

<div class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="delivery">
                <div class="col-12 pb-50">
                    <h2>Способы доставки</h2>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="contact-from contact-shadow ml-0">
                        <?= \yii\helpers\Html::img('/images/delivery-novaposhta.png',
                                                   [
                                                       'class' => 'col-12 pb-20',
                                                       'alt' => 'novaposhta',
                                                   ]) ?>
                        <p>
                            Доставка в отделение "Новая Почта"
                            <br>
                            <br>
                            С помощью "Новой Почты" вы сможете получить свой товар даже в самых отдаленных уголках
                            Украины.
                            <br>
                            Стоимость доставки может варьироваться от 50 до 400 грн в зависимости от веса и габаритов
                            товара.
                            <br>
                            Среднее время доставки товара 1-3 дня. Обращаем ваше внимание на то, что отправка заказов
                            производится с понедельника по пятницу в 12:00. Во время заказа наши менеджера согласуют с
                            вами дату доставки перед отправкой товара.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
