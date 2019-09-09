<?php


/** @var string $totalCost */

/** @var string $delivery */
/** @var array $items */
/** @var \app\modules\user\models\forms\LoginForm $loginForm */
/** @var \app\models\CheckoutForm $checkoutForm */
/** @var \app\modules\user\models\forms\SignupForm $checkoutForm ->signup */
/** @var \app\components\models\NovaPoshta $np */

$this->title = 'Оформление заказа';

$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

?>

<?php if (!empty($items) and !empty($checkoutForm)): ?>

    <div class="checkout-main-area pt-70 pb-70">
        <div class="container">
            <?php if (Yii::$app->user->isGuest and !empty($loginForm)): ?>
                <div class="customer-zone mb-20">
                    <p class="cart-page-title">Постоянный клиент? <a class="checkout-click1" href="#">Нажмите, чтобы
                            войти</a>
                    </p>
                    <div class="checkout-login-info">
                        <p>
                            Если вы покупали у нас раньше, то пожалуйста войдите с помощью формы внизу. Если вы новый
                            покупатель, пожалуйста перейдите к форме "Оформления заказа"
                        </p>
                        <?php $form = ActiveForm::begin([
                                                            'id' => 'login-form',
                                                        ]) ?>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="sin-checkout-login">
                                    <?= $form->field($loginForm, 'email')->textInput([
                                                                                         'placeholder' => $loginForm->getAttributeLabel('email'),
                                                                                     ])->label(false) ?>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="sin-checkout-login">
                                    <?= $form->field($loginForm, 'password')->passwordInput([
                                                                                                'placeholder' => $loginForm->getAttributeLabel('password'),
                                                                                            ])->label(false) ?>
                                </div>
                            </div>
                        </div>
                        <div class="button-remember-wrap">
                            <?= Html::submitButton('Войти') ?>
                            <div class="checkout-login-toggle-btn">
                                <?= $form->field($loginForm, 'rememberMe')
                                         ->checkbox()
                                         ->label('Запомнить меня') ?>
                            </div>
                        </div>
                        <div class="lost-password">
                            <?= Html::a('Забыли пароль?', [ '/user/default/forgot-password' ]) ?>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="checkout-wrap pt-30">
                <?php $form = ActiveForm::begin([
                                                    'id' => 'checkout-form',
                                                ]); ?>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="billing-info-wrap mr-50">
                            <h3>Оформление</h3>
                            <!--                        Проверка на авторизацию пользователя-->
                            <?php if (Yii::$app->user->isGuest and !empty($checkoutForm->signup)): ?>
                                <div class="row">
                                    <div class="col-6">
                                        <?= $form->field($checkoutForm->signup, 'name') ?>
                                    </div>
                                    <div class="col-6">
                                        <?= $form->field($checkoutForm->signup, 'surname') ?>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <?= $form->field($checkoutForm->signup, 'email') ?>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <?= $form->field($checkoutForm->signup, 'phone') ?>
                                    </div>
                                    <div class="col-4 col-sm-3">
                                        <?= $form->field($checkoutForm, 'booleanSignup')
                                                 ->checkbox([
                                                                'template' => '{input}{label}{error}',
                                                            ]) ?>
                                    </div>
                                    <div class="col-8 offset-sm-1 col-sm-8">
                                        <?= $form->field($checkoutForm->signup, 'password',
                                                         [ 'options' => [ 'style' => $checkoutForm->booleanSignup ? 'display:block;' : 'display:none;', ] ])
                                                 ->passwordInput() ?>
                                    </div>

                                </div>
                            <?php endif; ?>

                            <div class="row py-3">
                                <div class="col-12">
                                    <?= $form->field($checkoutForm, 'city')
                                             ->widget(\kartik\select2\Select2::className(), [
                                                 'initValueText' => !empty($checkoutForm->city) ? Yii::$app->novaposhta->getCityNameByRef($checkoutForm->city) : null, // set the initial display text
                                                 'options' => [ 'placeholder' => 'Найти свой город' ],
                                                 'pluginOptions' => [
                                                     //                                                     'allowClear' => true,
                                                     'minimumInputLength' => 3,
                                                     'maximumInputLength' => 3,
                                                     'ajax' => [
                                                         'url' => '/main/checkout/get-city',
                                                         'dataType' => 'json',
                                                         'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                                         'cache' => true,
                                                     ],
                                                     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                                     'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                                     'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                                                 ],
                                             ]) ?>
                                </div>
                                <div class="col-12">
                                    <?= $form->field($checkoutForm, 'department')
                                             ->widget(\kartik\select2\Select2::className(), [
                                                 'initValueText' => !empty($checkoutForm->department) ? Yii::$app->novaposhta->getWarehouseNameByRef($checkoutForm->department) : null, // set the initial display text
                                                 'options' => [
                                                     'disabled' => !empty($checkoutForm->department) ? false : true,
                                                     'prompt' => '',
                                                     'placeholder' => 'Выберите удобное для вас отделение',
                                                 ],

                                             ]) ?>
                                </div>
                                <div class="col-12">
                                    <?= $form->field($checkoutForm, 'comment')->textarea() ?>
                                </div>
                            </div>


                            <!--                        <div class="row">-->
                            <!--                            <div class="col-lg-6 col-md-6">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>First Name <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-6 col-md-6">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Last Name <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Company Name <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12">-->
                            <!--                                <div class="billing-select mb-20">-->
                            <!--                                    <label>Country <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <select>-->
                            <!--                                        <option>Select a country</option>-->
                            <!--                                        <option>Azerbaijan</option>-->
                            <!--                                        <option>Bahamas</option>-->
                            <!--                                        <option>Bahrain</option>-->
                            <!--                                        <option>Bangladesh</option>-->
                            <!--                                        <option>Barbados</option>-->
                            <!--                                    </select>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Street Address <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input class="billing-address" placeholder="House number and street name"-->
                            <!--                                           type="text">-->
                            <!--                                    <input placeholder="Apartment, suite, unit etc." type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Town / City <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12 col-md-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>State / County <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12 col-md-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Postcode / ZIP <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12 col-md-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Phone <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-12 col-md-12">-->
                            <!--                                <div class="billing-info mb-20">-->
                            <!--                                    <label>Email Address <abbr class="required" title="required">*</abbr></label>-->
                            <!--                                    <input type="text">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                        </div>-->
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="your-order-area">
                            <h3>Ваш заказ</h3>
                            <div class="your-order-wrap gray-bg-4">
                                <div class="your-order-info-wrap">
                                    <div class="your-order-info">
                                        <ul>
                                            <li>Товар <span>Цена</span></li>
                                        </ul>
                                    </div>
                                    <div class="your-order-middle">
                                        <ul>
                                            <?php foreach ($items as $item): ?>
                                                <li><?= $item['name'] . ' x ' . $item['quantity'] ?>
                                                    <span> <?= $item['cost'] ?> </span></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="your-order-info order-subtotal">
                                        <ul>
                                            <li>Сумма <span><?= $totalCost ?> </span></li>
                                        </ul>
                                    </div>
                                    <div class="your-order-info order-shipping">
                                        <ul>
                                            <li>Доставка <p> <?= $delivery ?></p></li>
                                        </ul>
                                    </div>
                                    <div class="your-order-info order-total">
                                        <ul>
                                            <li>Итого <span><?= $totalCost ?> </span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="payment-method">
                                    <div class="pay-top sin-payment">
                                        <input id="payment_method_1" class="input-radio" type="radio" value="cheque"
                                               checked="checked" name="payment_method">
                                        <label for="payment_method_1"> Новая Почта</label>
                                        <div class="payment-box payment_method_bacs">
                                            <p>Оплата происходит по номеру накладной, которая создается на вас при
                                                оформлении заказа.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Place-order mt-40">
                                <?= Html::submitButton('Оформить заказ', [ 'id' => 'submit-order' ]) ?>
                            </div>
                        </div>
                    </div>

                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="checkout-main-area pt-170 pb-170">
        <div class="container">
            <h1>К сожалению ваша корзина пуста</h1>
        </div>
    </div>

<?php endif; ?>
