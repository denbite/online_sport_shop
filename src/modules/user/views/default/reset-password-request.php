<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Восстановление пароля';
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
                        <h4 style="color:#ff3535;"> Восстановление пароля </h4>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <?php $form = ActiveForm::begin([
                                                                        'id' => 'reset-password-request-form',
                                                                    ]) ?>
                                    <?= $form->field($model, 'email')->textInput([
                                                                                     'placeholder' => $model->getAttributeLabel('email'),
                                                                                 ])->label(false) ?>
                                    <div class="button-box">
                                        <?= Html::submitButton('Восстановить доступ') ?>
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