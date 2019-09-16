<?php


/** @var array $order */

?>

<div class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="offset-md-2 col-12 col-md-8">
                <div class="contact-from contact-shadow ml-0">
                    <h4>Ваш заказ: № <?= $order['id'] ?></h4>
                    <p>
                        Благодарим вас за оформление заказа.
                        <?= $order['phone_status'] == \app\models\Order::PHONE_STATUS_WAITING ? ' Наши менеджера свяжутся с вами в ближайшее время.' : ' Ваш заказ будет отправлен в ближайшее время. Мы уведомим вас об отправке.' ?>
                    </p>
                    <div id="thanks-socials" class="mt-120">
                        <p>Также вы можете подписаться на наши соцсети, чтобы первыми узнавать об акциях:</p>
                        <a href="https://instagram.com/aquista7" target="_blank"><i
                                    class="sli sli-social-instagram"></i></a>
                        <a href="https://facebook.com/storeaquista" target="_blank"><i
                                    class="sli sli-social-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
