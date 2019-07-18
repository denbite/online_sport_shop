<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-register-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <h4 style="color:#ff3535;"> Зарегистрироваться </h4>
                    </div>
                    <div class="tab-content">
                        <div id="lg2" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <?php $form = ActiveForm::begin([
                                                                        'id' => 'signup-form',
                                                                    ]) ?>
                                    <?= $form->field($model, 'username')
                                             ->textInput([ 'placeholder' => $model->getAttributeLabel('username'), ])
                                             ->label(false) ?>
                                    <?= $form->field($model, 'password')
                                             ->passwordInput([ 'placeholder' => $model->getAttributeLabel('password'), ])
                                             ->label(false) ?>
                                    <?= $form->field($model, 'email')
                                             ->textInput([ 'placeholder' => $model->getAttributeLabel('email') ])
                                             ->label(false) ?>
                                    <div class="button-box">
                                        <?= Html::submitButton('Зарегистрироваться') ?>
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