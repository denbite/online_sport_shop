<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="login-register-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <h4 style="color:#ff3535;"> Вход </h4>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <?php $form = ActiveForm::begin([
                                                                        'id' => 'login-form',
                                                                    ]) ?>
                                    <?= $form->field($model, 'username')->textInput([
                                        'placeholder' => $model->getAttributeLabel('username'),
                                    ])->label(false) ?>
                                    <?= $form->field($model, 'password')->passwordInput([
                                        'placeholder' => $model->getAttributeLabel('password'),
                                    ])->label(false) ?>
                                    <div class="button-box">
                                        <div class="login-toggle-btn">
                                            <?= $form->field($model, 'rememberMe')
                                                     ->checkbox()
                                                     ->label('Запомнить меня') ?>

                                            <?= Html::a('Забыли пароль?', [ '/user/default/forgot-password' ]) ?>
                                        </div>
                                        <?= Html::submitButton('Войти') ?>
                                    </div>
                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>